🚀 GUÍA DE INSTALACIÓN DEL PROYECTO
Sigue estos pasos detallados para configurar el entorno y poner en marcha la aplicación.

1. REQUISITOS PREVIOS (PROGRAMAS Y PAQUETES)
Debes instalar los siguientes componentes en el orden indicado:

🐘 PHP & Servidor Local
PHP 8.2 / XAMPP: Descargar XAMPP 8.2.12

Tutorial de instalación: Ver: https://www.youtube.com/watch?v=pwTbAKRiRvA

📦 Gestor de Dependencias PHP
COMPOSER: Descargar Composer (Click en Composer-Setup.exe)

Tutorial de instalación: https://www.youtube.com/watch?v=70FW3WDqKEc

🌿 Control de Versiones
GIT: Descargar Git para Windows (Elegir 64-bit Git for Windows Setup)

Tutorial de instalación: https://www.youtube.com/watch?v=jdXKwLNUfmg&t=1s

🛠️ Laravel Framework 12.44.0
Una vez instalado Git, abre la terminal y ejecuta:

Bash:
composer global require laravel/installer
Tutorial de instalación: https://www.youtube.com/watch?v=NdcB3bNRV50

🟢 Entorno de Ejecución JS
NODEJS: Descargar Node.js (Elegir Windows Installer .msi)

Tutorial de instalación: https://www.youtube.com/watch?v=UtD6HgIEs2I

🅰️ Angular CLI
Para instalar la versión específica requerida, abre la consola de Git y ejecuta:

Bash:
npm install -g @angular/cli@21.0.4
Tutorial de instalación: https://www.youtube.com/watch?v=V8gKV16MvOM

2. PASOS PARA INSTALAR EL PROYECTO
Una vez tengas todas las herramientas instaladas, sigue estos pasos para clonar y ejecutar el sistema:

🖥️ Paso 1: acceder al servidor de apache y Configurar el Backend (Laravel)
Abre tu terminal en la carpeta donde guardas tus proyectos.
escribe lo siguiente en la consola.
cd c: 
luego
cd xampp/htdocs/app-back
y luego Clona el repositorio:

Bash:
git clone https://github.com/myNameISBocchi/app-back.git
Entra a la carpeta: cd app-back

Instala las librerías: composer install

Crea tu archivo de configuración: cp .env.example .env

Genera la clave de seguridad: php artisan key:generate

y ingresa tambien el: php artisan storage:link

Activa Apache y MySQL en XAMPP, crea la base de datos y ejecuta: php artisan migrate:fresh --seed


🎨 Paso 2: Configurar el Frontend (Angular)
Abre una nueva terminal.

Clona el repositorio:

Bash:
git clone https://github.com/myNameISBocchi/FlorMontielFront.git
Entra a la carpeta: cd FlorMontielFront

Instala los paquetes de Node colocando en la consola: npm install

Inicia la aplicación: ng serve -o