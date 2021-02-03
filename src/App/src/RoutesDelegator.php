<?php

namespace App;

use Mezzio\Application;
use Psr\Container\ContainerInterface;

class RoutesDelegator
{
    public function __invoke(
        ContainerInterface $container,
        $serviceName,
        callable $callback
    ) {
        $app = $callback();

        $app->route('/payment/create[/]', [
            Handler\Contract\PaymentHandlerInterface::class
        ], ['POST'], 'payment.create');

        $app->route('/payment[/]', [
            Handler\Contract\PaymentHandlerInterface::class
        ], ['GET'], 'payment.list');

        $app->route('/payment/{id:\d+}', [
            Handler\Contract\PaymentHandlerInterface::class
        ], ['GET'], 'payment.details');

        $app->route('/payment/update/{id:\d+}', [
            Handler\Contract\PaymentHandlerInterface::class
        ], ['PUT'], 'payment.update');

        $app->route('/payment/destroy/{id:\d+}', [
            Handler\Contract\PaymentHandlerInterface::class
        ], ['DELETE'], 'payment.destroy');

        return $app;
    }
}
