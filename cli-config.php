<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use Doctrine\ORM\Tools\Console\ConsoleRunner;
require_once "vendor/autoload.php";


use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$paths = array("module/Application/src/Entity");
$isDevMode = true;


    
// the connection configuration
$dbParams = array(
    'driver'   => 'pdo_mysql',
    'user'     => 'udm_root',
    'password' => 'wpp',
    'dbname'   => 'udm_academy',
);

// Any way to access the EntityManager from  your application
$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);
$entityManager = EntityManager::create($dbParams, $config);

return ConsoleRunner::createHelperSet($entityManager);

