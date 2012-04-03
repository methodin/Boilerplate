<?php
define('LIB', 	dirname(__FILE__) . '/');
!file_exists(LIB . 'config.php') ?: require_once LIB . 'config.php';

// Symfony ClassLoader
require_once LIB . 'Symfony/Component/ClassLoader/UniversalClassLoader.php';
use Symfony\Component\ClassLoader\UniversalClassLoader;
$loader = new UniversalClassLoader();
$loader->register();
$loader->registerNamespace('Symfony', LIB);

// TWIG
require_once LIB . 'Twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem(TEMPLATES);
$twig = new Twig_Environment($loader);

// Intercept the request and route appropriately
require_once LIB . 'Router.php';
