<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    echo "Este script solo se puede ejecutar desde consola.\n";
    exit(1);
}

$sqlFile = __DIR__ . DIRECTORY_SEPARATOR . 'MysqlBikeStoreScript.sql';

if (!is_file($sqlFile)) {
    fwrite(STDERR, "No se encontró el archivo SQL: {$sqlFile}\n");
    exit(1);
}

$command = ['docker', 'compose', 'exec', '-T', 'db', 'mysql', '-uroot', '-proot_password'];

$descriptors = [
    0 => ['pipe', 'r'],
    1 => ['pipe', 'w'],
    2 => ['pipe', 'w'],
];

$process = proc_open($command, $descriptors, $pipes, __DIR__);

if (!is_resource($process)) {
    fwrite(STDERR, "No se pudo iniciar el proceso de importación.\n");
    exit(1);
}

$sqlHandle = fopen($sqlFile, 'rb');

if ($sqlHandle === false) {
    fwrite(STDERR, "No se pudo abrir el archivo SQL.\n");
    proc_terminate($process);
    exit(1);
}

stream_copy_to_stream($sqlHandle, $pipes[0]);
fclose($sqlHandle);
fclose($pipes[0]);

$stdout = stream_get_contents($pipes[1]);
$stderr = stream_get_contents($pipes[2]);
fclose($pipes[1]);
fclose($pipes[2]);

$exitCode = proc_close($process);

if ($stdout !== '') {
    echo $stdout;
}

if ($stderr !== '') {
    fwrite(STDERR, $stderr);
}

if ($exitCode !== 0) {
    fwrite(STDERR, "El import terminó con errores. Código: {$exitCode}\n");
    exit($exitCode);
}

echo "Importación completada correctamente.\n";