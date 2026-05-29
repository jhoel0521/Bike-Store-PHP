<?php
require_once __DIR__ . '/libs/functions.php';
ensureSeccion();
app_auth_logout();
redirigir(base_url() . 'login.php');
