## Instalación

 - Clonar el repositorio
 - Crear un schema de base de datos con algun cliente MySQL
 - Ejecutar los migrations del directorio `sql/` en orden
 - Crear un archivo `config.php` (Hay un ejemplo para copiar en `config.php.example`)
  - Configurar la base de datos creada y los usuarios correspondientes
 - Ejecutar `composer install`
 - Ejecutar `composer require phpmailer/phpmailer`
 - Ejecutar `composer require google/apiclient:^2.0`

- hay que descomentar en el php.ini la linea referida a la extension:
    extension=pdo_mysql y reiniciar el servidor. 


### Aclaración

Por cada objeto creado por usted mismo (Model o Controller), debera indicar a
composer que regenere el autoload:

```
composer dumpautoload
```

Si lo que se desea es agregar una nueva libreria de 3ero

```
composer requiere name/lib:version
```

## Deploy / ejecución

### Local

Ejecutar:

```
git clone https://github.com/jnatmed/juego-puzzle.git
cd juego_puzzle/
# Pasos de instalación
php -S localhost:8080
```

Luego ingresar a http://localhost:8080

