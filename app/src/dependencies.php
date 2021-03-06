<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use App\Services\EmailService;
use App\Services\PhoneService;
use App\Services\UserService;
use App\Services\MessageService;
use App\Services\MemcachedService;

// DIC configuration

$container = $app->getContainer();

$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container['email_service'] = function ($c) {
    return new EmailService($c->get('logger'));
};

$container['phone_service'] = function ($c) {
    return new PhoneService($c->get('logger'));
};

$container['user_service'] = function ($c) {
    return new UserService($c->get('logger'));
};

$container['message_service'] = function ($c) {
    return new MessageService($c->get('logger'));
};

$container['memcached'] = function ($c) {
    $m = new \Memcached();
    $m->addServer($c->get('settings')['memcached']['host'], $c->get('settings')['memcached']['port']);
    return $m;
};

$container['memcached_service'] = function ($c) {
    return new MemcachedService($c->get('logger'), $c['memcached']);
};

// bootstrap eloquent ORM
$capsule = new Capsule;
$capsule->addConnection($container->settings['db']);
$capsule->setEventDispatcher(new Dispatcher(new Container));
$capsule->setAsGlobal();
$capsule->bootEloquent();
