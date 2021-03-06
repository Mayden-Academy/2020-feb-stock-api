<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\Views\PhpRenderer;

return function (ContainerBuilder $containerBuilder) {
    $container = [];

    $container[LoggerInterface::class] = function (ContainerInterface $c) {
        $settings = $c->get('settings');

        $loggerSettings = $settings['logger'];
        $logger = new Logger($loggerSettings['name']);

        $processor = new UidProcessor();
        $logger->pushProcessor($processor);

        $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
        $logger->pushHandler($handler);

        return $logger;
    };

    $container['renderer'] = function (ContainerInterface $c) {
        $settings = $c->get('settings')['renderer'];
        $renderer = new PhpRenderer($settings['template_path']);
        return $renderer;
    };

    $container['Database'] = new App\Utilities\Database();

    $container['ProductModel'] = DI\factory('App\Factories\ProductModelFactory');

    $container['OrderModel'] = DI\factory('App\Factories\OrderModelFactory');

    $container['AddProductController'] = DI\factory('App\Factories\AddProductControllerFactory');
    $container['GetProductsController'] = DI\factory('App\Factories\GetProductsControllerFactory');
    $container['GetProductBySKUController'] = DI\factory('App\Factories\GetProductBySKUControllerFactory');
    $container['UpdateProductController'] = DI\factory('\App\Factories\UpdateProductControllerFactory');
    $container['UpdateProductStockController'] = DI\factory('App\Factories\UpdateProductStockControllerFactory');
    $container['ReinstateProductController'] = DI\factory('App\Factories\ReinstateProductControllerFactory');
    $container['DeleteProductController'] = DI\factory('App\Factories\DeleteProductControllerFactory');

    $container['AddOrderController'] = DI\factory('App\Factories\AddOrderControllerFactory');
    $container['GetOrdersController'] = DI\factory('App\Factories\GetOrdersControllerFactory');
    $container['CompleteOrderController'] = DI\factory('App\Factories\CompleteOrderControllerFactory');
    $container['CancelOrderController'] = DI\factory('App\Factories\CancelOrderControllerFactory');

    $containerBuilder->addDefinitions($container);
};
