<?php

require_once 'vendor/autoload.php';

use App\DataFixtures\AppFixtures;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Doctrine\ORM\ORMSetup;
use Symfony\Component\Dotenv\Dotenv;

// --- ENV VARS
$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/.env');
// --- ENV VARS

// --- DOCTRINE
$paths = ['src/Entity'];
$isDevMode = $_ENV['APP_ENV'] === 'dev';

$dbParams = [
    'driver'   => $_ENV['DB_DRIVER'],
    'host'     => $_ENV['DB_HOST'],
    'port'     => $_ENV['DB_PORT'],
    'user'     => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
    'dbname'   => $_ENV['DB_NAME']
];

$config = ORMSetup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);
$entityManager = EntityManager::create($dbParams, $config);

$driver = new AttributeDriver($paths);
$entityManager->getConfiguration()->setMetadataDriverImpl($driver);
// --- DOCTRINE

// Create the loader and register your fixtures
$loader = new Loader();
$loader->addFixture(new AppFixtures());

// Purge existing data (optional, if you want to start with a clean database)
$purger = new ORMPurger($entityManager);
$purger->purge();

// Load the fixtures
$executor = new Doctrine\Common\DataFixtures\Executor\ORMExecutor($entityManager, $purger);
$executor->execute($loader->getFixtures());
