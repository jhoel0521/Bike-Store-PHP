# Bike Store

Proyecto PHP simple para administrar categorías y productos.

## Requisitos

- Docker Desktop
- Node.js 18+ para usar los comandos `npm run ...`

## Clonar y levantar

```bash
git clone <URL-del-repositorio>
cd bike_store
copy .env.example .env
npm run dev
```

La app queda disponible en:

- http://localhost:8080

## Comandos

```bash
npm run dev
npm run docker:up
npm run docker:run-script-db
npm run docker:db
npm run docker:logs
npm run docker:ps
npm run docker:restart
npm run docker:down
```

`npm run dev` usa Compose Watch para sincronizar cambios en caliente dentro del contenedor de la app.

## Base de datos

El contenedor MySQL carga automáticamente el archivo `MysqlBikeStoreScript.sql` al crear el volumen por primera vez.

Si quieres reiniciar la base de datos desde cero:

```bash
npm run docker:down
```

Luego elimina el volumen `db_data` desde Docker o vuelve a crear el entorno limpio.

## Notas

- La conexión de PHP lee estas variables: `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`.
- Si lo ejecutas fuera de Docker, `bd.php` usa valores por defecto locales.

**Seeder**

- **Propósito:** importar `MysqlBikeStoreScript.sql` dentro del contenedor `app`.
- **Comando npm:** ejecuta el seeder usando el script definido en `package.json`.

```bash
npm run docker:run-script-db
```

- **Comportamiento:** el script primero intenta `docker compose exec -T app php run-seeder-prod.php` (útil cuando `app` ya está corriendo, p. ej. con `npm run dev`).
- Si `exec` falla (servicio no iniciado), hace `docker compose build app` y luego `docker compose run --rm --no-deps app php run-seeder-prod.php`, garantizando que `run-seeder-prod.php` exista en la imagen.
- Ejecuta el seeder en una conexión interna a `db`, por lo que debe correrse dentro del entorno Docker o especificando variables `DB_HOST` correctas.

Ejemplos:

```bash
# Cuando tienes la app en modo desarrollo en otra terminal
npm run dev        # en otra terminal
npm run docker:run-script-db

# Si no tienes la app corriendo, el script hará build y correrá el seeder
npm run docker:run-script-db
```
