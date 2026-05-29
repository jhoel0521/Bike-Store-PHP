# Tareas (rechazo rápido)

0. Cambiar eliminaciones por método DELETE
- Uso actual: enlaces GET que eliminan registros (riesgo y malas prácticas).
- Acción: migrar a formularios o peticiones `DELETE` desde fetch/AJAX; validar CSRF.

1. Contraseñas en claro — pendiente
- Problema: el seed inserta contraseñas en texto plano.
- Acción: usar `password_hash()` para generar hashes y `password_verify()` en login.
- Tarea adicional: crear script para rehash o forzar restablecimiento de contraseñas.

2. Validación y seguridad de uploads — pendiente
- Problema: `move_uploaded_file` sin validar mime/tamaño ni sanitizar nombres.
- Acción: validar MIME y extensión, límite de tamaño, usar nombres únicos, y almacenar fuera del webroot o con control de acceso.

3. CSRF — pendiente
- Problema: formularios no usan token CSRF.
- Acción: implementar helper de CSRF (generar/verificar token en sesión) y añadir al formulario `form.php` y demás.

4. Configuración por entorno — ya está (docker-compose.yml)
- Observación: el `docker-compose.yml` en desarrollo define variables de entorno.
- Recomendación: renombrarlo a `.dev.yml` o añadir `.env.example` y documentar.
- Archivo: [docker-compose.yml](docker-compose.yml)

5. Manejo de errores y logging — pendiente
- Problema: `bd.php` muestra errores directamente y puede revelar información sensible.
- Acción: configurar un logger (Monolog u otro) y mostrar mensajes genéricos al usuario en producción; habilitar `display_errors` solo en desarrollo.

6. Hardening de sesión — aceptable por ahora
- Observación: `ensureSeccion()` funciona; para entornos productivos sugerir ajustar `session.cookie_httponly`, `secure`, `SameSite`.
- Para aprendizaje: dejar por ahora, documentar mejoras futuras.

---

Si quieres, implemento en este orden prioritario:
1) `password_hash` + `password_verify` (login + seed),
2) validación de uploads en `secciones/productos/crear.php` y `editar.php`,
3) CSRF global.

Indica qué paso quieres que haga ahora.