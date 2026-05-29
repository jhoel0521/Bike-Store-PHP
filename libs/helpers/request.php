<?php

/**
 * Procesa la solicitud HTTP actual y devuelve un objeto que permite
 * acceder a los datos y archivos de forma fluida.
 * Combina $_GET, $_POST y $_FILES, y también puede manejar JSON en el cuerpo.
 * Permite acceder a los datos mediante métodos como `get()` o propiedades mágicas.
 *
 * Example:
 * $request = request();
 * echo $request->method(); // GET, POST, etc.
 * echo $request->get('username'); // Accede a $_GET['username'] o $_POST['username']
 * if ($request->hasFile('avatar')) {
 *   $avatarInfo = $request->file('avatar'); // Accede a $_FILES['avatar']
 * }
 *
 * @return object Instancia de clase anónima con los datos del request
 */
function request()
{
    static $instance = null;

    if ($instance === null) {
        $instance = new class {
            private string $method;
            private array $data  = [];
            private array $files = [];

            public function __construct()
            {
                $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
                $this->data   = array_merge($_GET, $_POST);
                $this->files  = $_FILES;

                $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
                if (strpos($contentType, 'application/json') !== false) {
                    $input    = file_get_contents('php://input');
                    $jsonData = json_decode($input, true);
                    if (is_array($jsonData)) {
                        $this->data = array_merge($this->data, $jsonData);
                    }
                }
            }

            /**
             * Devuelve el método HTTP de la solicitud (GET, POST, etc.)
             */
            public function method(): string
            {
                return $this->method;
            }

            /**
             * Devuelve todos los datos de la solicitud.
             * Combina $_GET, $_POST y JSON (si aplica).
             *
             * @return array<string,mixed>
             */
            public function all(): array
            {
                return $this->data;
            }

            /**
             * Obtiene un valor específico de los datos de la solicitud.
             * Si no está en `$_GET`/`$_POST`, intenta devolver el archivo
             * correspondiente desde `$_FILES`.
             *
             * @param string $key
             * @param mixed $default
             * @return mixed
             */
            public function get(string $key, $default = null)
            {
                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists($key, $this->files)) {
                    return $this->files[$key];
                }

                return $default;
            }

            /**
             * Obtiene la información de un archivo específico.
             * Equivale a `$_FILES['key']` o a una parte concreta como
             * `name`, `tmp_name`, `size` o `type`.
             *
             * Example:
             * $archivo = request()->file('foto');
             * $nombre  = request()->file('foto', 'name');
             *
             * @param string $key
             * @param string|null $part Parte del archivo a devolver
             * @param mixed $default
             * @return mixed
             */
            public function file(string $key, ?string $part = null, $default = null)
            {
                if (!isset($this->files[$key])) {
                    return $default;
                }

                if ($part === null) {
                    return $this->files[$key];
                }

                return $this->files[$key][$part] ?? $default;
            }

            /**
             * Verifica si un archivo fue enviado en la petición y si realmente
             * se subió algo (sin error de "archivo vacío").
             */
            public function hasFile(string $key): bool
            {
                return isset($this->files[$key]) && $this->files[$key]['error'] !== UPLOAD_ERR_NO_FILE;
            }

            /**
             * Devuelve todos los archivos de la petición.
             */
            public function allFiles(): array
            {
                return $this->files;
            }

            /**
             * Permite acceder a los datos de la solicitud mediante propiedades mágicas.
             *
             * @param string $key
             * @return mixed
             */
            public function __get(string $key)
            {
                return $this->get($key);
            }
        };
    }

    return $instance;
}
