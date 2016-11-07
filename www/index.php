<?php
use App\Application,
    App\Config;

function env($key, $defaultValue)
{
    return getenv($key) ?? $defaultValue;
}

require_once(__DIR__ . '/vendor/autoload.php');

$configData = require_once ('config/common.php');
$config = new Config($configData);

$application = new Application($config);
echo $application->run();
