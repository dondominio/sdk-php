# The DonDominio/MrDomain API SDK for PHP

Leverage the powerful DonDominio/MrDomain API in your own applications, projects, and websites
using our easy-to-use SDK for PHP.

Install the library, include one class, and you will be making requests to the DonDominio/MrDomain
API in a matter of seconds.

The [Wiki](https://github.com/dondominio/sdk-php/wiki/) contains information on how to get started and about everything you may need.

For more information, documentation, support, and guides, visit
[dev.mrdomain.com/api/docs/sdk-php/](https://dev.mrdomain.com/api/docs/sdk-php/).

# A Simple Example

```php
<?php

// Load the DonDominio SDK autoloader
require_once "/path/to/lib/src/autoloader.php";

// Create an instance of the DonDominio API Client
$dondominio = new \Dondominio\API\API([
    'apiuser' => "YOUR_API_USER",
    'apipasswd' => "YOUR_API_PASSWORD"
]);

// Request the account information to the API
$response = $dondominio->account_info();

// Get the API Response in Array and print it
print_r($response->getResponseData());

```

You can see more examples in [examples file](https://github.com/dondominio/sdk-php/tree/master/examples)

# SDK para PHP de la API de DonDominio/MrDomain

Saca partido de la poderosa API de DonDominio/MrDomain en tus propias aplicaciones, proyectos y 
páginas web usando nuestro sencillo SDK para PHP.

Instala la librería, incluye una clase y estarás haciendo peticiones a la API de DonDominio/MrDomain
en cuestión de segundos.

La [Wiki](https://github.com/dondominio/sdk-php/wiki/) contiene información sobre como empezar y prácticamente todo lo que puedas necesitar.

Para más información, documentación, soporte y guías, visita
[dev.dondominio.com/api/docs/sdk-php/](https://dev.dondominio.com/api/docs/sdk-php/).

# Codigo de ejemplo

```php
<?php

// Carga el autoloader del SDK de DonDominio
require_once "/path/to/lib/src/autoloader.php";

// Crea una instancia del cliente de la API de DonDominio
$dondominio = new \Dondominio\API\API([
    'apiuser' => "YOUR_API_USER",
    'apipasswd' => "YOUR_API_PASSWORD"
]);

// Consulta a la API la información de la cuenta
$response = $dondominio->account_info();

// Obtiene la respuesta de la API en formato Array y lo imprime
print_r($response->getResponseData());

```

Puedes consultar más ejemplos en el [fichero de ejemplos](https://github.com/dondominio/sdk-php/tree/master/examples)
