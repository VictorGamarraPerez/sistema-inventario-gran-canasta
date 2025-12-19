# Instrucciones para subir el proyecto a GitHub sin Git

## Archivos/Carpetas que NO debes subir:
- ❌ `/vendor` (muy pesado, se reinstala con composer)
- ❌ `/node_modules` (muy pesado, se reinstala con npm)
- ❌ `.env` (información sensible)
- ❌ `/storage/logs/laravel.log` (logs temporales)

## Archivos que SÍ debes subir:
- ✅ Todos los archivos `.php`
- ✅ `/app`, `/config`, `/database`, `/routes`, `/resources`
- ✅ `/public` (excepto `/public/storage`)
- ✅ `composer.json` y `composer.lock`
- ✅ `package.json` y `package-lock.json`
- ✅ `.gitignore`
- ✅ `artisan`, `README.md`, `vite.config.js`, `phpunit.xml`

## Pasos para subir:

### 1. Crear repositorio en GitHub
- Ve a https://github.com/new
- Nombre: `sistema-inventario-gran-canasta`
- Tipo: Private (recomendado)
- NO marques "Add README" ni ".gitignore"
- Click "Create repository"

### 2. Subir archivos por lotes
GitHub tiene límite de 100 archivos por subida y 25MB por archivo.

**Lote 1: Archivos raíz**
- Arrastra: `artisan`, `composer.json`, `composer.lock`, `package.json`, `package-lock.json`, `phpunit.xml`, `README.md`, `vite.config.js`, `.gitignore`

**Lote 2: Carpeta `/app`**
- Click "Add file" → "Upload files"
- Arrastra toda la carpeta `/app`

**Lote 3: Carpeta `/config`**
- Sube toda la carpeta `/config`

**Lote 4: Carpeta `/database`**
- Sube toda la carpeta `/database`

**Lote 5: Carpeta `/routes`**
- Sube toda la carpeta `/routes`

**Lote 6: Carpeta `/resources`**
- Sube toda la carpeta `/resources`

**Lote 7: Carpeta `/public`** (sin /public/storage)
- Sube los archivos de `/public`: `index.php`, `robots.txt`

**Lote 8: Carpeta `/bootstrap`**
- Sube toda la carpeta `/bootstrap`

**Lote 9: Carpeta `/tests`**
- Sube toda la carpeta `/tests`

**Lote 10: Estructura de `/storage`**
- Solo sube la estructura de carpetas vacías con sus archivos `.gitignore`

### 3. Crear archivo .env.example
En lugar de subir tu `.env`, crea un `.env.example` con valores de ejemplo:
- Copia tu `.env` y renómbralo a `.env.example`
- Reemplaza valores sensibles:
  - DB_PASSWORD= (déjalo vacío)
  - APP_KEY= (déjalo vacío)
  - Cualquier otro dato sensible

## Instrucciones para instalar desde el repositorio:

```bash
# 1. Clonar repositorio (cuando alguien descargue)
git clone https://github.com/tu-usuario/sistema-inventario-gran-canasta.git

# 2. Instalar dependencias
composer install
npm install

# 3. Configurar entorno
cp .env.example .env
php artisan key:generate

# 4. Configurar base de datos en .env y migrar
php artisan migrate

# 5. Crear enlace simbólico para storage
php artisan storage:link
```
