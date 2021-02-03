<?php

namespace AppTest\Unit\Factory\Service;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use App\Factory\Service\PaymentServiceFactory;
use App\Service\Contract\PaymentServiceInterface;
use App\Repository\Contract\PaymentUnitOfWorkInterface;
use App\Service\PaymentService;

class PaymentServiceFactoryUnitTest extends TestCase
{
    protected $container;

    protected function setUp() : void
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $paymentUnitOfWork = $this->prophesize(PaymentUnitOfWorkInterface::class);
        $this->container
          ->get(PaymentUnitOfWorkInterface::class)
          ->willReturn($paymentUnitOfWork);
    }

    public function testCanCreatePaymentService()
    {
        $factory = new PaymentServiceFactory();
        $serviceHandler = $factory($this->container->reveal());
        $this->assertInstanceOf(PaymentService::class, $serviceHandler);
    }
}
