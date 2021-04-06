<?php

use App\Board;
use App\Player;
use App\TikTacToe;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

// Handle EXceptions
$app->error(function (\Exception $e, $code) use ($app) {
  if ($e instanceof Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
    return $app->json(['error' => 'Page Not Found'], 404);
  }
  if ($e instanceof Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
    return $app->json(['error' => 'Page Not Found'], 405);
  }
  return $app->json(['error' => 'Server Error'], 500);
});

// Registing the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => '../logs/tictactoe.log',
));
// Registering service providers
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

// Declare controller dependencies
$app["player"] = function () {
  return new Player();
};
$app["board"] = function () {
  return new Board();
};

// Define controller as a service and inject the needed dependencies.
$app["app.tiktactoe"] = function () use ($app) {
  return new TikTacToe($app['player'], $app['board']);
};

// Routing
$app->get('/', 'app.tiktactoe:base');

$app->run();
