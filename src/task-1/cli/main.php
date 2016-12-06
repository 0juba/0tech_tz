<?php

$loader = require_once __DIR__ . '/../vendor/autoload.php';

$container = require_once __DIR__ . '/../Resources/config/container.php';

$container['verifierService']->verifyAll();
