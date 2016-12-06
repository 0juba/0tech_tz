<?php

define('BATCH_SIZE', 1000);

$container = array();

$chainVerifier = new \Lib\Verifier\ChainVerifier(array(
    new \Lib\Verifier\EmailRFCVerifier(),
    new \Lib\Verifier\DomainExistenceVerifier(),
    new \Lib\Verifier\EmailExistenceVerifier(),
));
$container['chainEmailVerifier'] = $chainVerifier;

$container['logger'] = new \Lib\Logger\BlackHoleLogger();

$connection = new \PDO('mysql:host=localhost;dbname=demo;charset=utf8', 'user', 'password');
$container['db'] = $connection;

$container['verifierCommand'] = new \Lib\Command\VerifyEmailsCommand(
    $container['chainEmailVerifier'],
    $container['db'],
    $container['logger'],
    BATCH_SIZE
);

$container['verifierService'] = new \Lib\Service\EmailVerifierService(
    $container['db'],
    $container['verifierCommand'],
    BATCH_SIZE
);

return $container;
