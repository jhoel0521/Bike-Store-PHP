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

$host = getenv('DB_HOST') ?: 'db';
$user = getenv('DB_ROOT_USER') ?: 'root';
$password = getenv('DB_ROOT_PASSWORD') ?: 'root_password';

try {
    importar_con_pdo($sqlFile, $host, $user, $password);
    echo "Importación completada correctamente.\n";
    exit(0);
} catch (PDOException $e) {
    fwrite(
        STDERR,
        "Este seeder está pensado para ejecutarse dentro del contenedor app, " .
        "donde el host 'db' es resolvible. Error: " . $e->getMessage() . "\n"
    );
    exit(1);
}

/**
 * Separa un archivo SQL en sentencias individuales ignorando comentarios.
 *
 * @param string $sql Contenido completo del archivo SQL.
 * @return array<int, string> Lista de sentencias limpias listas para ejecutar.
 */
function separar_sentencias_sql(string $sql): array
{
    $sentencias = [];
    $buffer = '';
    $longitud = strlen($sql);
    $enCadena = false;
    $comilla = '';
    $enComentarioLinea = false;
    $enComentarioBloque = false;

    for ($i = 0; $i < $longitud; $i++) {
        $caracter = $sql[$i];
        $siguiente = $i + 1 < $longitud ? $sql[$i + 1] : '';

        if ($enComentarioLinea) {
            if ($caracter === "\n") {
                $enComentarioLinea = false;
            }
            continue;
        }

        if ($enComentarioBloque) {
            if ($caracter === '*' && $siguiente === '/') {
                $enComentarioBloque = false;
                $i++;
            }
            continue;
        }

        if (!$enCadena && $caracter === '-' && $siguiente === '-') {
            $caracterAnterior = $i > 0 ? $sql[$i - 1] : "\n";
            if ($caracterAnterior === "\n" || $caracterAnterior === "\r") {
                $enComentarioLinea = true;
                $i++;
                continue;
            }
        }

        if (!$enCadena && $caracter === '#') {
            $enComentarioLinea = true;
            continue;
        }

        if (!$enCadena && $caracter === '/' && $siguiente === '*') {
            $enComentarioBloque = true;
            $i++;
            continue;
        }

        if ($caracter === '\'' || $caracter === '"') {
            if ($enCadena && $comilla === $caracter) {
                $anterior = $i > 0 ? $sql[$i - 1] : '';
                if ($anterior !== '\\') {
                    $enCadena = false;
                    $comilla = '';
                }
            } elseif (!$enCadena) {
                $enCadena = true;
                $comilla = $caracter;
            }
        }

        if ($caracter === ';' && !$enCadena) {
            $sentencia = trim($buffer);
            if ($sentencia !== '') {
                $sentencias[] = $sentencia;
            }
            $buffer = '';
            continue;
        }

        $buffer .= $caracter;
    }

    $sentenciaFinal = trim($buffer);
    if ($sentenciaFinal !== '') {
        $sentencias[] = $sentenciaFinal;
    }

    return $sentencias;
}

/**
 * Importa el archivo SQL usando una conexión PDO directa.
 *
 * @param string $sqlFile Ruta del archivo SQL.
 * @param string $host Host de la base de datos.
 * @param string $user Usuario root.
 * @param string $password Contraseña root.
 * @return void
 */
function importar_con_pdo(string $sqlFile, string $host, string $user, string $password): void
{
    $pdo = new PDO(
        "mysql:host={$host};charset=utf8mb4",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    $sql = file_get_contents($sqlFile);

    if ($sql === false) {
        throw new RuntimeException('No se pudo leer el archivo SQL.');
    }

    $sentencias = separar_sentencias_sql($sql);

    foreach ($sentencias as $sentencia) {
        $pdo->exec($sentencia);
    }
}
