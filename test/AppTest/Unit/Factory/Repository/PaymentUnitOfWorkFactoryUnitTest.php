<?php

namespace AppTest\Unit\Factory\Repository;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use App\Factory\Repository\PaymentUnitOfWorkFactory;
use App\Repository\Contract\PaymentUnitOfWorkInterface;
use App\Repository\PaymentUnitOfWork;
use Doctrine\ORM\EntityManager;

class PaymentUnitOfWorkFactoryUnitTest extends TestCase
{
    protected $container;

    protected function setUp() : void
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $entityManager = $this->prophesize(EntityManager::class);
        $this->container
          ->get('doctrine.entity_manager.orm_default')
          ->willReturn($entityManager);
    }

    public function testCanCreatePaymentUnitOfWork()
    {
        $factory = new PaymentUnitOfWorkFactory();
        $paymentHandler = $factory($this->container->reveal());
        $this->assertInstanceOf(PaymentUnitOfWork::class, $paymentHandler);
    }
}
