## Proyecto App Financiera

## Iniciar Proyecto
-  Clonar Proyecto: git clone https://github.com/claudito/app-financiera.git
-  Crear la base de datos: externa_app
-  Duplicar .env.example a .env
-  Ejecutar Composer(Ejecutar por Consola): composer install --ignore-platform-reqs
-  Ejecutar por Consola:  php artisan key:generate
-  Generar LLave Secreta: php artisan jwt:secret
-  Cargar Migraciones y Seeder(Ejecutar por Consola) : php artisan migrate:fresh --seed
-  Cargar Ruta de Test(Ejecutar por Consola) : php artisan serve --host=127.0.0.1 --port=8000 
-  Ejecutar npm Install: npm install
-  Ejecutar : npm run dev

## Requisitos mínimos
-  PHP 8 o superior
-  MySQL >= 8.0 o MariaDB >= 10.5
-  Tener Instalado [ Composer ](https://getcomposer.org/download/).

## Paquetes Instalados
-   [ Laravel 10 ](https://laravel.com/docs/10.x).
-   [ Laravel  JWT Auth ](https://jwt-auth.readthedocs.io/en/develop/laravel-installation/).
