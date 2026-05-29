<?php include("templates/header.php"); ?>
<div class="row g-4 align-items-stretch">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 app-hero h-100">
            <div class="card-body p-4 p-lg-5 d-flex flex-column justify-content-between">
                <div>
                    <?php if (app_is_logged_in()) { ?>
                    <span class="badge bg-primary-subtle text-primary rounded-pill mb-3">Administración</span>
                    <?php } ?>
                    <h1 class="display-5 fw-bold mb-3">Bike Store</h1>
                    <p class="lead text-secondary mb-4">
                        <?php if (app_is_logged_in()) { ?>
                        Una interfaz limpia para gestionar categorías, productos, clientes y empleados.
                        <?php } else { ?>
                        Explora nuestro catálogo de bicicletas y accesorios.
                        <?php } ?>
                    </p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-primary px-4 rounded-pill" href="<?= base_url() ?>secciones/productos/">
                        <i class="bi bi-bicycle me-2"></i>Ver productos
                    </a>
                    <?php if (app_is_logged_in()) { ?>
                    <a class="btn btn-outline-secondary px-4 rounded-pill" href="<?= base_url() ?>secciones/categorias/">
                        <i class="bi bi-tags me-2"></i>Ver categorías
                    </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 3rem; height: 3rem;">
                        <i class="bi bi-speedometer2 fs-4"></i>
                    </div>
                    <div>
                        <p class="text-secondary small mb-1">Panel de control</p>
                        <h2 class="h4 mb-0">Acceso rápido</h2>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-12">
                        <a href="<?= base_url() ?>secciones/productos/" class="card border-0 shadow-sm rounded-4 text-decoration-none text-dark h-100">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-secondary small mb-1">Inventario</p>
                                    <h3 class="h5 mb-0">Productos</h3>
                                </div>
                                <i class="bi bi-arrow-right-circle text-primary fs-3"></i>
                            </div>
                        </a>
                    </div>
                    <?php if (app_is_logged_in()) { ?>
                    <div class="col-12">
                        <a href="<?= base_url() ?>secciones/categorias/" class="card border-0 shadow-sm rounded-4 text-decoration-none text-dark h-100">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-secondary small mb-1">Catálogo</p>
                                    <h3 class="h5 mb-0">Categorías</h3>
                                </div>
                                <i class="bi bi-arrow-right-circle text-primary fs-3"></i>
                            </div>
                        </a>
                    </div>
                    <div class="col-12">
                        <a href="<?= base_url() ?>secciones/clientes/" class="card border-0 shadow-sm rounded-4 text-decoration-none text-dark h-100">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-secondary small mb-1">Directorio</p>
                                    <h3 class="h5 mb-0">Clientes</h3>
                                </div>
                                <i class="bi bi-arrow-right-circle text-primary fs-3"></i>
                            </div>
                        </a>
                    </div>
                    <div class="col-12">
                        <a href="<?= base_url() ?>secciones/empleados/" class="card border-0 shadow-sm rounded-4 text-decoration-none text-dark h-100">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-secondary small mb-1">Equipo</p>
                                    <h3 class="h5 mb-0">Empleados</h3>
                                </div>
                                <i class="bi bi-arrow-right-circle text-primary fs-3"></i>
                            </div>
                        </a>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("templates/footer.php"); ?>
