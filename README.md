Solución prueba técnica (Avanzza)

Laravel 9

Base de datos:
MySQL
DB_DATABASE=avanzza

Ir a la carpeta del proyecto y ejecutar "php artisan serve" en la consola

Todas las url's necesitan el parámetro "token" igual a "12345" en el header de la petición

URLS:
GET http://127.0.0.1:8000/api/ficheros
POST http://127.0.0.1:8000/api/ficheros
DELETE http://127.0.0.1/avanzza/public/api/ficheros/{idFichero}
DELETE http://127.0.0.1/avanzza/public/api/ficheros/delete-fisico/{idFichero}

Cada url tiene un máximo de 3 solicitudes por minuto