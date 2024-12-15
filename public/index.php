<?php

use Victor\Sae51\Controllers\HomeController;
use Victor\Sae51\Router;

require_once __DIR__ . '/../vendor/autoload.php';

// Charger les configurations
require_once __DIR__ . '/../src/Config/app.php';

Router::run();
