<?php

namespace App\Factory\Service;

use Psr\Container\ContainerInterface;
use App\Service\PaymentService;
use App\Service\Contract\PaymentServiceInterface;
use App\Repository\Contract\PaymentUnitOfWorkInterface;

class PaymentServiceFactory
{
    public function __invoke(
        ContainerInterface $container
    ): PaymentServiceInterface {
        $paymentUnitOfWork = $container->get(PaymentUnitOfWorkInterface::class);
        return new PaymentService($paymentUnitOfWork);
    }
}
