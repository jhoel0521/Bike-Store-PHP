<?php

/**
 * Devuelve la URL base de la aplicación obtenida de la variable de
 * entorno `BASE_URL`. Si no existe, devuelve una URL por defecto
 * adecuada para entornos de desarrollo locales.
 *
 * Example:
 * echo base_url(); // -> "http://localhost:8080/" (según .env.example o configuración)
 *
 * @return string URL base terminada en '/'
 */
function base_url(): string
{
    $baseUrl = getenv('BASE_URL');
    $isHttps = false;

    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        $isHttps = true;
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
        $isHttps = strtolower((string) $_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https';
    } elseif (isset($_SERVER['SERVER_PORT']) && (string) $_SERVER['SERVER_PORT'] === '443') {
        $isHttps = true;
    }

    $scheme = $isHttps ? 'https' : 'http';

    if ($baseUrl === false || $baseUrl === '') {
        return $scheme . '://localhost/Bike-Store-PHP/';
    }

    $baseUrl = rtrim($baseUrl, '/') . '/';

    $parsedUrl = parse_url($baseUrl);

    if ($parsedUrl === false) {
        return $scheme . '://localhost/Bike-Store-PHP/';
    }

    if (isset($parsedUrl['scheme']) && $parsedUrl['scheme'] !== $scheme) {
        $baseUrl = preg_replace('/^https?:\/\//i', $scheme . '://', $baseUrl) ?? $baseUrl;
    }

    return $baseUrl;
}

/**
 * Inicia la sesión PHP si no está ya activa.
 *
 * Garantiza que `$_SESSION` esté disponible antes de usarlo.
 *
 * Example:
 * ensureSeccion();
 *
 * @return void
 */
function ensureSeccion(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

/**
 * Alias/compatibilidad para iniciar la sesión.
 *
 * Simplemente delega en `ensureSeccion()` para mantener llamadas
 * anteriores que usen `iniciar_sesion()`.
 *
 * Example:
 * iniciar_sesion();
 *
 * @return void
 */
function iniciar_sesion(): void
{
    ensureSeccion();
}

/**
 * Dump and die: muestra una representación de una o varias variables y
 * detiene la ejecución. Acepta múltiples argumentos, por ejemplo:
 *
 * Example:
 * dd('hola', $_GET);
 * dd($user);
 *
 * @param mixed ...$valores Uno o varios valores a mostrar (variadic)
 * @return void
 */
function dd(...$valores): void
{
    echo '<pre>';
    foreach ($valores as $v) {
        var_dump($v);
    }
    echo '</pre>';
    exit;
}

/**
 * Establece un mensaje "flash" en la sesión para mostrarse tras una
 * redirección. Guarda el mensaje y su tipo (p. ej. 'success', 'error').
 *
 * Example:
 * flash_set('Guardado correctamente', 'success');
 *
 * @param string $mensaje Texto del mensaje a mostrar
 * @param string $tipo Tipo visual del mensaje (por ejemplo 'success'|'error'|'info')
 * @return void
 */
function flash_set(string $mensaje, string $tipo = 'success'): void
{
    ensureSeccion();
    $_SESSION['flash'] = [
        'mensaje' => $mensaje,
        'tipo' => $tipo,
    ];
}

/**
 * Establece un mensaje flash y redirige a la ruta indicada.
 *
 * Example:
 * redirigir_con_mensaje('/productos/index.php', 'Producto creado', 'success');
 *
 * @param string $ruta Ruta o URL destino (relativa o absoluta)
 * @param string $mensaje Mensaje a mostrar tras la redirección
 * @param string $tipo Tipo de mensaje (ver `flash_set`)
 * @return void
 */
function redirigir_con_mensaje(string $ruta, string $mensaje, string $tipo = 'success'): void
{
    flash_set($mensaje, $tipo);
    redirigir($ruta);
}
/**
 * Redirige inmediatamente a la ruta indicada.
 *
 * Example:
 * redirigir('/login.php');
 *
 * @param string $ruta Ruta o URL destino
 * @return void
 */
function redirigir(string $ruta): void
{
    header('Location: ' . $ruta);
    exit;
}

/**
 * Devuelve la ruta del script actual dentro de la aplicación.
 *
 * @return string
 */
function app_current_script_path(): string
{
    return str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
}

/**
 * Indica si el script actual es público.
 *
 * @return bool
 */
function app_is_public_script(): bool
{
    if (php_sapi_name() === 'cli') {
        return true;
    }

    $appPath = rtrim((string) (parse_url(base_url(), PHP_URL_PATH) ?? ''), '/');
    $scriptPath = app_current_script_path();

    return in_array($scriptPath, [
        $appPath . '/login.php',
        $appPath . '/cerrar.php',
    ], true);
}

/**
 * Devuelve el usuario autenticado actual o null si no existe sesión de acceso.
 *
 * @return array<string,mixed>|null
 */
function app_current_user(): ?array
{
    ensureSeccion();

    return $_SESSION['auth'] ?? null;
}

/**
 * Indica si hay una sesión de autenticación activa.
 *
 * @return bool
 */
function app_is_logged_in(): bool
{
    return app_current_user() !== null;
}

/**
 * Guarda los datos del usuario autenticado en la sesión.
 *
 * @param array<string,mixed> $registro
 * @return void
 */
function app_auth_login(array $registro): void
{
    ensureSeccion();
    session_regenerate_id(true);

    $_SESSION['auth'] = [
        'user_id' => (int) ($registro['user_id'] ?? 0),
        'user' => (string) ($registro['user'] ?? ''),
        'email' => (string) ($registro['email'] ?? ''),
        'role' => (string) ($registro['role'] ?? ''),
    ];

    $_SESSION['usuario'] = $_SESSION['auth']['user'];
    $_SESSION['role'] = $_SESSION['auth']['role'];
}

/**
 * Cierra la sesión del usuario actual.
 *
 * @return void
 */
function app_auth_logout(): void
{
    ensureSeccion();

    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }

    session_destroy();
}

/**
 * Redirige al login si la sesión no está autenticada.
 *
 * @return void
 */
function app_require_auth(): void
{
    if (php_sapi_name() === 'cli' || app_is_public_script()) {
        return;
    }

    if (!app_is_logged_in()) {
        redirigir(base_url() . 'login.php');
    }
}

/**
 * Redirige al inicio si el usuario no tiene rol administrador.
 *
 * @return void
 */
function app_require_admin(): void
{
    app_require_auth();

    $usuario = app_current_user();

    if (($usuario['role'] ?? '') !== 'admin') {
        redirigir_con_mensaje(base_url() . 'index.php', 'No tienes permisos para acceder a esta sección.', 'error');
    }
}

/**
 * Convierte el rol técnico en una etiqueta legible.
 *
 * @param string $role
 * @return string
 */
function app_role_label(string $role): string
{
    return match ($role) {
        'admin' => 'Administrador',
        'empleado' => 'Empleado',
        default => ucfirst($role),
    };
}


/**
 * Extrae el mensaje flash (si existe) y devuelve el HTML/JS necesario
 * para mostrarlo mediante SweetAlert. El mensaje se elimina de la sesión
 * tras su lectura.
 *
 * Example:
 * echo flash_render();
 *
 * @return string HTML/JS del modal o cadena vacía si no hay flash
 */
function flash_render(): string
{
    $flash = null;

    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
    }

    if ($flash === null) {
        return '';
    }

    $tipo = json_encode($flash['tipo'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $mensaje = json_encode($flash['mensaje'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    return <<<HTML
<script>
    Swal.fire({
        icon: {$tipo},
        title: {$mensaje}
    });
</script>
HTML;
}

/**
 * Procesa la solicitud HTTP actual y devuelve un objeto que permite
 * acceder a los datos y archivos de forma fluida.
 * Combina $_GET, $_POST y $_FILES, y también puede manejar JSON en el cuerpo.
 * Permite acceder a los datos mediante métodos como `get()` o propiedades mágicas.
 * Example:
 * $request = request();
 * echo $request->method(); // GET, POST, etc.
 * echo $request->get('username'); // Accede a $_GET['username'] o $_POST['username']
 * if ($request->hasFile('avatar')) {
 *   $avatarInfo = $request->file('avatar'); // Accede a $_FILES['avatar']
 * }
 * 
 * * @return object Instancia de clase anónima con los datos del request
 */
function request()
{
    static $instance = null;

    if ($instance === null) {
        $instance = new class {
            private string $method;
            private array $data = [];
            private array $files = []; // Nuevo contenedor para archivos

            public function __construct()
            {
                $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

                // Combinamos GET y POST por defecto
                $this->data = array_merge($_GET, $_POST);

                // Guardamos los archivos subidos
                $this->files = $_FILES;

                // Si la petición es JSON, leemos el cuerpo y lo combinamos
                $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
                if (strpos($contentType, 'application/json') !== false) {
                    $input = file_get_contents('php://input');
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
             * Devuelve todos los datos de la solicitud
             * combina $_GET, $_POST y JSON (si aplica)
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
             * $nombre = request()->file('foto', 'name');
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
             * Devuelve todos los archivos de la petición
             */
            public function allFiles(): array
            {
                return $this->files;
            }

            /**
             * Permite acceder a los datos de la solicitud mediante propiedades mágicas
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
