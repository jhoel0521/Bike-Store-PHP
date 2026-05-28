<?php
require_once __DIR__ . '/../libs/functions.php';
ensureSeccion();
?>
<!doctype html>
<html lang="es" data-bs-theme="light">

<head>
    <title>Bike Store</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Tienda de bicicletas en PHP con Bootstrap 5" />
    <!-- icon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url() ?>assets/bike.png" />
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
                    <ul class="navbar-nav ms-auto gap-lg-1">
                        <li class="nav-item">
                            <a class="nav-link rounded-pill px-3" href="<?= base_url() ?>"><i class="bi bi-house-door me-1"></i>Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link rounded-pill px-3" href="<?= base_url() ?>secciones/categorias/"><i class="bi bi-tags me-1"></i>Categorías</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link rounded-pill px-3" href="<?= base_url() ?>secciones/productos/"><i class="bi bi-bicycle me-1"></i>Productos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link rounded-pill px-3" href="<?= base_url() ?>secciones/clientes/"><i class="bi bi-people me-1"></i>Clientes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link rounded-pill px-3" href="<?= base_url() ?>secciones/empleados/"><i class="bi bi-briefcase me-1"></i>Empleados</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="container py-4 py-lg-5 flex-grow-1">