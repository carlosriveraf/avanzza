Solución prueba técnica (Avanzza)<br><br>
Laravel 9<br><br>
Base de datos:<br>
MySQL<br>
DB_DATABASE=avanzza<br><br>
Ir a la carpeta del proyecto y ejecutar "php artisan serve" en la consola.<br><br>
Todas las url's necesitan el parámetro "token" igual a "12345" en el header de la petición.<br><br>
URLS:<br>
GET http://127.0.0.1:8000/api/ficheros<br>
POST http://127.0.0.1:8000/api/ficheros<br>
DELETE http://127.0.0.1/avanzza/public/api/ficheros/{idFichero}<br>
DELETE http://127.0.0.1/avanzza/public/api/ficheros/delete-fisico/{idFichero}<br><br>
Cada url tiene un máximo de 3 solicitudes por minuto