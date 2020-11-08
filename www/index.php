<?php

declare(strict_types=1);

// path to application folder
define('APP_DIR', __DIR__ . '/../app');

// absolute path to public WWW dir
define('WWW_DIR', __DIR__);

require __DIR__ . '/../vendor/autoload.php';

App\Bootstrap::boot()
	->createContainer()
	->getByType(Nette\Application\Application::class)
	->run();
