<?php

namespace App\Factory\Handler;

use Psr\Container\ContainerInterface;
use App\Handler\Contract\PaymentHandlerInterface;
use App\Service\Contract\PaymentServiceInterface;
use App\Handler\PaymentHandler;

class PaymentHandlerFactory
{
    public function __invoke(
        ContainerInterface $container
    ): PaymentHandlerInterface {
        $paymentService = $container->get(PaymentServiceInterface::class);
        return new PaymentHandler($paymentService);
    }
}
