<?php
require_once __DIR__ . '/../libs/functions.php';
ensureSeccion();
$usuarioActual = app_current_user();
?>
<!doctype html>
<html lang="es" data-bs-theme="light">

<head>
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') . ' — Bike Store' : 'Bike Store' ?></title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="<?= isset($pageDescription) ? htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') : 'Gestiona tu catálogo de bicicletas: productos, categorías, clientes y empleados en un solo lugar.' ?>" />
    <meta name="robots" content="index, follow" />
    <meta name="author" content="Bike Store" />
    <!-- Open Graph -->
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="Bike Store" />
    <meta property="og:title" content="<?= isset($pageTitle) ? htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') . ' — Bike Store' : 'Bike Store' ?>" />
    <meta property="og:description" content="<?= isset($pageDescription) ? htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') : 'Gestiona tu catálogo de bicicletas: productos, categorías, clientes y empleados en un solo lugar.' ?>" />
    <meta property="og:image" content="<?= base_url() ?>assets/bike.png" />
    <meta property="og:url" content="<?= htmlspecialchars((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? '') . ($_SERVER['REQUEST_URI'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" />
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:title" content="<?= isset($pageTitle) ? htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') . ' — Bike Store' : 'Bike Store' ?>" />
    <meta name="twitter:description" content="<?= isset($pageDescription) ? htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') : 'Gestiona tu catálogo de bicicletas: productos, categorías, clientes y empleados en un solo lugar.' ?>" />
    <meta name="twitter:image" content="<?= base_url() ?>assets/bike.png" />
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url() ?>assets/bike.png" />
    <link rel="shortcut icon" href="<?= base_url() ?>assets/bike.png" />
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
        crossorigin="anonymous" />
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/app.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.bootstrap5.min.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="d-flex flex-column min-vh-100 bg-light text-dark">
    <header class="sticky-top">
        <nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm">
            <div class="container">
                <a class="navbar-brand fw-semibold text-primary d-flex align-items-center gap-2" href="<?= base_url() ?>">
                    <img src="<?= base_url() ?>assets/bike.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
                    Bike Store
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Alternar navegación">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainNavbar">
                    <ul class="navbar-nav ms-auto gap-lg-1 align-items-lg-center">
                        <li class="nav-item">
                            <a class="nav-link rounded-pill px-3" href="<?= base_url() ?>"><i class="bi bi-house-door me-1"></i>Inicio</a>
                        </li>
                        <?php if (app_is_logged_in()) { ?>
                            <li class="nav-item">
                                <a class="nav-link rounded-pill px-3" href="<?= base_url() ?>secciones/categorias/"><i class="bi bi-tags me-1"></i>Categorías</a>
                            </li>
                        <?php } ?>
                        <li class="nav-item">
                            <a class="nav-link rounded-pill px-3" href="<?= base_url() ?>secciones/productos/"><i class="bi bi-bicycle me-1"></i>Productos</a>
                        </li>
                        <?php if (app_is_logged_in()) { ?>
                            <li class="nav-item">
                                <a class="nav-link rounded-pill px-3" href="<?= base_url() ?>secciones/pedidos/"><i class="bi bi-receipt me-1"></i>Pedidos</a>
                            </li>
                        <?php } ?>
                        <?php if (app_is_logged_in()) { ?>
                            <li class="nav-item">
                                <a class="nav-link rounded-pill px-3" href="<?= base_url() ?>secciones/clientes/"><i class="bi bi-people me-1"></i>Clientes</a>
                            </li>
                            <?php if (($usuarioActual['role'] ?? '') === 'admin') { ?>
                                <li class="nav-item">
                                    <a class="nav-link rounded-pill px-3" href="<?= base_url() ?>secciones/usuarios/"><i class="bi bi-person-gear me-1"></i>Empleados</a>
                                </li>
                            <?php } ?>
                        <?php } ?>
                        <?php if (app_is_logged_in()) { ?>
                            <li class="nav-item ms-lg-3">
                                <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-pill bg-light border small">
                                    <span class="badge bg-primary-subtle text-primary rounded-pill"><?= htmlspecialchars(app_role_label((string) ($usuarioActual['role'] ?? '')), ENT_QUOTES, 'UTF-8') ?></span>
                                    <span class="fw-semibold"><?= htmlspecialchars((string) ($usuarioActual['user'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
                                    <a class="btn btn-sm btn-outline-secondary rounded-pill" href="<?= base_url() ?>cerrar.php">Salir</a>
                                </div>
                            </li>
                        <?php } else { ?>
                            <li class="nav-item ms-lg-2">
                                <a class="btn btn-sm btn-primary rounded-pill px-3" href="<?= base_url() ?>login.php">
                                    <i class="bi bi-box-arrow-in-right me-1"></i>Iniciar sesión
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="container py-4 py-lg-5 flex-grow-1">