<?php require_once __DIR__ . '/../../bd.php';
app_require_admin();

if (request()->method() === 'POST') {
    $user = trim((string) request()->get('user', ''));
    $password = (string) request()->get('password', '');
    $email = trim((string) request()->get('email', ''));
    $role = (string) request()->get('role', 'empleado');

    if ($user === '' || $password === '' || $email === '' || $role === '') {
        $mensaje = 'Todos los campos son obligatorios.';
    } else {
        $existe = (int) \DB::getValor(
            "SELECT COUNT(*) FROM users WHERE user = :user OR email = :email",
            [':user' => $user, ':email' => $email]
        );

        if ($existe > 0) {
            $mensaje = 'El usuario o el email ya existe.';
        } else {
            \DB::ejecutarConsulta(
                "INSERT INTO users (user, password, email, role) VALUES (:user, :password, :email, :role)",
                [
                    ':user' => $user,
                    ':password' => $password,
                    ':email' => $email,
                    ':role' => $role,
                ]
            );
            redirigir_con_mensaje('index.php', 'Registro agregado');
        }
    }
}

$txtID = '';
$user = '';
$password = '';
$email = '';
$role = 'empleado';
$accion = 'Agregar';
?>
<?php include("../../templates/header.php"); ?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
    <div>
        <span class="badge bg-primary-subtle text-primary rounded-pill mb-2">Seguridad</span>
        <h1 class="h3 fw-bold mb-1">Nuevo usuario</h1>
        <p class="text-secondary mb-0">Crea credenciales y asigna un rol de acceso.</p>
    </div>
    <a class="btn btn-outline-secondary px-4 rounded-pill" href="index.php" role="button">Volver</a>
</div>
<?php include("form.php"); ?>

<?php include("../../templates/footer.php"); ?>