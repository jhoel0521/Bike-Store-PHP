<?php require_once __DIR__ . '/../../bd.php';
app_require_admin();

$txtID = '';
$user = '';
$password = '';
$email = '';
$role = 'empleado';

if (request()->method() === 'POST') {
    $txtID = (string) request()->get('txtID', '');

    if ($txtID === '') {
        redirigir_con_mensaje('index.php', 'Debes seleccionar un usuario válido.', 'warning');
    }

    $registroActual = \DB::getRegistro("SELECT * FROM users WHERE user_id = :id", [':id' => $txtID]);

    if (!$registroActual) {
        redirigir_con_mensaje('index.php', 'El usuario solicitado no existe.', 'error');
    }

    $user = trim((string) request()->get('user', ''));
    $password = (string) request()->get('password', '');
    $email = trim((string) request()->get('email', ''));
    $role = (string) request()->get('role', 'empleado');

    if ($user === '' || $email === '' || $role === '') {
        $mensaje = 'El usuario, el email y el rol son obligatorios.';
    } else {
        $existe = (int) \DB::getValor(
            "SELECT COUNT(*) FROM users WHERE (user = :user OR email = :email) AND user_id <> :id",
            [
                ':user' => $user,
                ':email' => $email,
                ':id' => $txtID,
            ]
        );

        if ($existe > 0) {
            $mensaje = 'El usuario o el email ya existe en otro registro.';
        } else {
            $passwordFinal = $password !== '' ? $password : (string) $registroActual['password'];

            \DB::ejecutarConsulta(
                "UPDATE users SET user = :user, password = :password, email = :email, role = :role WHERE user_id = :id",
                [
                    ':id' => $txtID,
                    ':user' => $user,
                    ':password' => $passwordFinal,
                    ':email' => $email,
                    ':role' => $role,
                ]
            );

            if ((int) ($registroActual['user_id'] ?? 0) === (int) (app_current_user()['user_id'] ?? 0)) {
                $_SESSION['auth']['user'] = $user;
                $_SESSION['auth']['email'] = $email;
                $_SESSION['auth']['role'] = $role;
                $_SESSION['usuario'] = $user;
                $_SESSION['role'] = $role;
            }

            redirigir_con_mensaje('index.php', 'Registro actualizado');
        }
    }
} else {
    $txtID = (string) request()->get('txtID', '');

    if ($txtID !== '') {
        $registro = \DB::getRegistro("SELECT * FROM users WHERE user_id = :id", [':id' => $txtID]);

        if (!$registro) {
            redirigir_con_mensaje('index.php', 'El registro solicitado no existe.', 'error');
        }

        $user = (string) $registro['user'];
        $email = (string) $registro['email'];
        $role = (string) $registro['role'];
    } else {
        redirigir_con_mensaje('index.php', 'Debes seleccionar un usuario válido.', 'warning');
    }
}

$accion = 'Actualizar';
?>
<?php include("../../templates/header.php"); ?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
    <div>
        <span class="badge bg-primary-subtle text-primary rounded-pill mb-2">Seguridad</span>
        <h1 class="h3 fw-bold mb-1">Editar usuario</h1>
        <p class="text-secondary mb-0">Actualiza credenciales, email y rol de acceso.</p>
    </div>
    <a class="btn btn-outline-secondary px-4 rounded-pill" href="index.php" role="button">Volver</a>
</div>
<?php include("form.php"); ?>
<?php include("../../templates/footer.php"); ?>