# Sistema de Inventario Gran Canasta

Sistema de gestión de inventario desarrollado con Laravel 11 para control de productos, entradas, salidas y reportes.

## 📋 Características

- **Gestión de Productos**: CRUD completo de productos con categorías
- **Proveedores**: Administración de proveedores
- **Entradas y Salidas**: Control de movimientos de inventario
- **Reportes**: Generación de reportes en PDF y Excel
- **Autenticación**: Sistema de login con verificación por código de email
- **Roles de Usuario**: Sistema de permisos (Administrador, Gerente, Empleado)
- **Panel de Control**: Dashboard con estadísticas en tiempo real

## 🛠️ Tecnologías Utilizadas

- **Framework**: Laravel 11
- **Base de Datos**: MySQL
- **Frontend**: Blade Templates, TailwindCSS, Alpine.js
- **Librerías**:
  - DomPDF para generación de PDFs
  - Maatwebsite/Excel para exportación de datos
  - Chart.js para gráficos

## 📦 Requisitos del Sistema

- PHP >= 8.2
- Composer
- MySQL >= 5.7
- Node.js >= 18
- NPM >= 9

## 🚀 Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/victorgp2098/sistema-inventario-gran-canasta.git
cd sistema-inventario-gran-canasta
```

### 2. Instalar dependencias de PHP

```bash
composer install
```

### 3. Instalar dependencias de Node.js

```bash
npm install
```

### 4. Configurar variables de entorno

```bash
cp .env.example .env
```

Edita el archivo `.env` y configura tu base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventario_gran_canasta
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 5. Generar clave de aplicación

```bash
php artisan key:generate
```

### 6. Ejecutar migraciones

```bash
php artisan migrate
```

### 7. Compilar assets

```bash
npm run build
```

O para desarrollo:

```bash
npm run dev
```

### 8. Iniciar servidor

```bash
php artisan serve
```

La aplicación estará disponible en `http://localhost:8000`

## 🔧 Configuración Adicional

### Configurar Email (Opcional)

Para habilitar el envío de códigos de verificación por email, configura en `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_email@gmail.com
MAIL_PASSWORD=tu_contraseña_app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Crear Usuario Administrador

Puedes crear un usuario desde la base de datos o usando tinker:

```bash
php artisan tinker
```

```php
User::create([
    'name' => 'Administrador',
    'email' => 'admin@grancanasta.com',
    'password' => bcrypt('password'),
    'role' => 'admin',
    'active' => true
]);
```

## 👥 Roles y Permisos

- **Administrador**: Acceso total al sistema
- **Gerente**: Acceso a productos, entradas, salidas y reportes
- **Empleado**: Acceso limitado a consultas

## 📝 Uso

1. Accede a `http://localhost:8000/login`
2. Ingresa con tu email y contraseña
3. Verifica el código que recibirás por email (si está configurado)
4. Comienza a gestionar tu inventario

## 🤝 Contribución

Las contribuciones son bienvenidas. Por favor, abre un issue primero para discutir qué te gustaría cambiar.

## 📄 Licencia

Este proyecto es privado y está bajo los derechos de autor de Gran Canasta.

## 👨‍💻 Autor

**Víctor Gamarra Pérez**
- Email: victorgp2098@gmail.com
- GitHub: [@victorgp2098](https://github.com/victorgp2098)

---

Desarrollado con ❤️ para Gran Canasta

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
