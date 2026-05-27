<?php require_once __DIR__ . '/../libs/functions.php'; ?>
    </main>
    <footer class="mt-auto border-top bg-white">
        <div class="container py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 small text-secondary">
            <span class="fw-semibold text-dark">Bike Store</span>
            <span>Gestión simple, rápida y ordenada.</span>
        </div>
    </footer>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?= flash_render() ?>
</body>
</html>