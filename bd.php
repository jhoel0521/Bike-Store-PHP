<?php

require_once __DIR__ . '/libs/functions.php';
ensureSeccion();
app_require_auth();

/**
 * Clase `DB` — Helper para conexión y ejecución de consultas usando PDO.
 * Proporciona un singleton de conexión y métodos utilitarios para ejecutar
 * consultas y obtener resultados en distintos formatos.
 */
class DB
{

    private static $servidor = "127.0.0.1";
    private static $baseDeDatos = "Bike_Store";
    private static $usuario = "root";
    private static $contrasena = "";
    private static ?PDO $conexion = null;

    private static function obtenerConfiguracion(string $clave, string $valorPorDefecto): string
    {
        $valor = getenv($clave);
        return $valor !== false && $valor !== '' ? $valor : $valorPorDefecto;
    }

    public static function conectar()
    {
        if (self::$conexion == null) {
            self::$conexion = self::crearConexion();
        }
        return self::$conexion;
    }
    /**
     * Retorna la instancia única de PDO (patrón singleton).
     *
     * @return PDO Conexión PDO activa
     */
    private static function crearConexion()
    {
        $servidor = self::obtenerConfiguracion('DB_HOST', self::$servidor);
        $baseDeDatos = self::obtenerConfiguracion('DB_NAME', self::$baseDeDatos);
        $usuario = self::obtenerConfiguracion('DB_USER', self::$usuario);
        $contrasena = self::obtenerConfiguracion('DB_PASSWORD', self::$contrasena);
        try {
            $conexion = new PDO(
                "mysql:host=" . $servidor . ";dbname=" . $baseDeDatos,
                $usuario,
                $contrasena
            );
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conexion;
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            exit;
        }
    }
    /**
     * Cierra la conexión establecida poniendo la instancia en `null`.
     *
     * @return void
     */
    public static function desconectar()
    {
        self::$conexion = null;
    }

    /**
     * Prepara y ejecuta una consulta SQL con parámetros.
     * Ideal para operaciones `INSERT`, `UPDATE`, `DELETE` o cuando
     * se necesita acceder al `PDOStatement` para operar sobre resultados.
     *
     * Example:
     * $consulta = "INSERT INTO users (username, password) VALUES (:username, :password)";
     * $parametros = [':username' => $username, ':password' => $password];
     * DB::ejecutarConsulta($consulta, $parametros);
     *
     * @param string $consulta Sentencia SQL a ejecutar
     * @param array $parametros Parámetros asociados a la sentencia (nombre => valor)
     * @return PDOStatement Sentencia preparada y ejecutada
     */
    public static function ejecutarConsulta($consulta, $parametros = [])
    {
        $conexion = self::conectar();
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute($parametros);
        return $sentencia;
    }

    /**
     * Ejecuta una consulta y devuelve todas las filas como un arreglo asociativo.
     *
     * @param string $consulta
     * @param array $parametros
     * @return array<int,array<string,mixed>> Array de filas (assoc)
     */
    public static function getTabla($consulta, $parametros = [])
    {
        $sentencia = self::ejecutarConsulta($consulta, $parametros);
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ejecuta una consulta y devuelve la primera fila como arreglo asociativo.
     * Si no hay resultados devuelve `false`.
     *
     * @param string $consulta
     * @param array $parametros
     * @return array<string,mixed>|false
     */
    public static function getRegistro($consulta, $parametros = [])
    {
        $sentencia = self::ejecutarConsulta($consulta, $parametros);
        return $sentencia->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Ejecuta una consulta y devuelve el valor de la primera columna
     * de la primera fila (útil para COUNT(*) u otras agregaciones).
     *
     * @param string $consulta
     * @param array $parametros
     * @return mixed
     */
    public static function getValor($consulta, $parametros = [])
    {
        $sentencia = self::ejecutarConsulta($consulta, $parametros);
        return $sentencia->fetchColumn();
    }
}

