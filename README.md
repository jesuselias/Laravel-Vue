<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

Instrucciones de ejecución del proyecto:

Backend (PHP/Laravel)

Utilicé PHP como lenguaje de programación y Laravel como framework.
Implementé pruebas unitarias utilizando PHPUnit.
La base de datos utilizada fue PostgreSQL.
Realicé varias migraciones para crear y llenar las tablas con los datos requeridos.
Para llenar las tablas, utilicé:

```bash
php artisan tinker 
```
 php artisan tinker 

Despues por ejemplo para crear un Hote:

```bash
$hotel = App\Models\Hotel::create(['name' => 'HotelLegs']);
```

Para ejecutar el servidor:

```bash
php artisan serve
```

Frontend (Vue.js/Pinia)

Desarrollé la interfaz de usuario utilizando Vue.js.
Implementé Pinia como gestor de estado.
Para ejecutar el frontend, se utiliza el comando:

```bash
npm run dev
```


acceder a http://localhost:8000/.
 
Pruebas Unitarias y Automatización

En el backend, las pruebas unitarias se pueden ejecutar con:

```bash
npm run testphpunit
```

o

```bash
./vendor/bin/phpunit
```

En el frontend, implementé pruebas unitarias con Jest para ejecutar:

```bash
npm run testjest
```

o

```bash
npx jest
```

y agregue Cypress para automatización de pruebas. 

Los comandos para ejecutar Cypress son:

```bash
npm run cypress:run 
```

```bash
npm run cypress:open
```
 
