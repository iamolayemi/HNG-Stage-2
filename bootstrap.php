<?php

require __DIR__ . '/vendor/autoload.php';
require_once 'config.php';
require_once 'functions.php';


// Smarty Configurations
$smarty = new Smarty;
$smarty->setTemplateDir(__DIR__ . '/templates');
$smarty->setCompileDir(__DIR__ . '/resources/templates_compiled');
$smarty->setCacheDir(__DIR__ . '/resources/cache');
$smarty->caching = Smarty::CACHING_LIFETIME_CURRENT;
$smarty->debugging = false;


