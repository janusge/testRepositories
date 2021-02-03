<?php

namespace App\Factory\Repository;

use Psr\Container\ContainerInterface;
use App\Repository\Contract\PaymentUnitOfWorkInterface;
use App\Repository\PaymentUnitOfWork;

class PaymentUnitOfWorkFactory
{
    public function __invoke(
        ContainerInterface $container
    ): PaymentUnitOfWorkInterface
    {
      $entityManager = $container->get('doctrine.entity_manager.orm_default');
      return new PaymentUnitOfWork($entityManager);
    }
}
