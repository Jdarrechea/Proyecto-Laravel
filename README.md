# Zapadictos

Proyecto Laravel para administrar productos de calzado y promociones.

## Características

- CRUD de productos
- CRUD de promociones
- Catálogo visual con imágenes de zapatillas
- Descarga de catálogo en PDF
- Soporte de carga de imagen para productos
- Base de datos SQLite lista para usar

## Rutas principales

- `/productos` — lista y gestión de productos
- `/catalogo` — catálogo con tarjetas de zapatillas
- `/promociones` — lista y gestión de promociones
- `/productos-pdf` — descarga del catálogo en PDF

## Requisitos

- PHP 8+
- Composer
- Extensiones PHP: `fileinfo`, `pdo_sqlite`, `mbstring`, `curl`

## Instalación

1. Copia el archivo `.env.example` a `.env`:

```bash
copy .env.example .env
```

2. Instala dependencias:

```bash
composer install
```

3. Genera la clave de la aplicación:

```bash
php artisan key:generate
```

4. Crea el archivo SQLite:

```bash
type nul > database\database.sqlite
```

5. Ejecuta migraciones:

```bash
php artisan migrate
```

6. Crea el enlace de almacenamiento:

```bash
php artisan storage:link
```

7. Inicia el servidor:

```bash
php artisan serve --host=localhost --port=8000
```

8. Abre en el navegador:

```text
http://localhost:8000
```

## Ajustes de imagen

- Al crear o editar un producto, se puede subir una imagen de zapatilla.
- Las imágenes se guardan en `storage/app/public/productos`.
- El catálogo visual muestra la foto adaptada dentro de la tarjeta.

## Nota

El proyecto utiliza SQLite para una configuración simple y rápida. Si deseas cambiar a MySQL, actualiza las variables en `.env` y ejecuta las migraciones nuevamente.

## Licencia

MIT
