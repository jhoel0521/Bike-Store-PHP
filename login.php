<?php
require_once __DIR__ . '/bd.php';
ensureSeccion();

$errorMessage = false;
$mensaje = '';

if ($_POST) {
    $usuario = $_POST['usuario'] ?? '';
    $clave = $_POST['clave'] ?? '';

    $registro = \DB::getRegistro(
        "SELECT *, COUNT(*) as n_usuario FROM usuarios WHERE usuario=:usuario AND clave=:clave",
        [
            ':usuario' => $usuario,
            ':clave' => $clave,
        ]
    );

    if ($registro) {
        $_SESSION['usuario'] = $registro['usuario'];
        $_SESSION['n_usuario'] = $registro['n_usuario'];
        header('Location: index.php');
        exit;
    }

    $mensaje = 'Error: El usuario o la contraseña son incorrectos.';
    $errorMessage = true;
}
?>
<!doctype html>
<html lang="es" data-bs-theme="light">

<head>
    <title>Identificación de Usuario</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
        crossorigin="anonymous" />
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/app.css" />
</head>

<body class="d-flex flex-column min-vh-100 bg-light text-dark">
    <main class="container flex-grow-1 d-flex align-items-center py-5">
        <div class="row g-0 justify-content-center w-100">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="row g-0">
                        <div class="col-md-5 app-hero p-4 p-lg-5 d-flex flex-column justify-content-between">
                            <div>
                                <span class="badge bg-primary rounded-pill mb-3">Bike Store</span>
                                <h1 class="h3 fw-bold">Acceso al panel</h1>
                                <p class="text-secondary mb-0">
                                    Una entrada limpia y rápida para administrar tu catálogo.
                                </p>
                            </div>
                            <div class="mt-4 text-secondary small">
                                <i class="bi bi-shield-check me-1 text-primary"></i>Sesión simple y directa.
                            </div>
                        </div>
                        <div class="col-md-7 bg-white">
                            <div class="card-body p-4 p-lg-5">
                                <h2 class="h4 fw-bold mb-1">Iniciar sesión</h2>
                                <p class="text-secondary mb-4">Ingresa tus credenciales para continuar.</p>

                                <?php if ($errorMessage) : ?>
                                    <div class="alert alert-danger border-0 rounded-3" role="alert">
                                        <?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?>
                                    </div>
                                <?php endif; ?>

                                <form action="" method="post" class="row g-3">
                                    <div class="col-12">
                                        <label for="usuario" class="form-label text-secondary fw-semibold">Usuario</label>
                                        <input type="text" class="form-control form-control-lg rounded-3" name="usuario" id="usuario" placeholder="Ingrese su usuario">
                                    </div>
                                    <div class="col-12">
                                        <label for="clave" class="form-label text-secondary fw-semibold">Contraseña</label>
                                        <input type="password" class="form-control form-control-lg rounded-3" name="clave" id="clave" placeholder="Ingrese su contraseña">
                                    </div>
                                    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                        <a href="#" class="text-decoration-none small">Recordar contraseña</a>
                                        <button type="submit" class="btn btn-primary px-4 rounded-pill">Ingresar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>
