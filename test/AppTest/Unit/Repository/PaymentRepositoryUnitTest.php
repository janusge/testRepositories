<?php

namespace AppTest\Unit\Repository;

use App\Repository\PaymentRepository;
use App\Exception\EntityNotFoundException;
use App\Exception\RepositoryException;
use App\Entity\Dummy\DummyEntity;
use AppTest\Unit\Repository\Base\DbTestCase;
use App\Entity\Factory\PaymentFactory;

class PaymentRepositoryUnitTest extends DbTestCase
{
    protected $paymentFactory;

    protected function setUp(): void
    {
      parent::setUp();
      $this->paymentFactory = new PaymentFactory($this->entityManager);
    }

    public function testCanAddWithArrayPayment()
    {
        $repository = new PaymentRepository($this->entityManager);
        $attributes = [
          'reference' => 12345678,
          'amount' => 100000,
          'description' => 'description',
        ];

        $payment = $repository->addWithArray($attributes);
        $this->entityManager->flush();
        $id = $payment->getId();

        $this->assertTrue(!!$id);
    }

    public function testCanAddPayment()
    {
        $repository = new PaymentRepository($this->entityManager);
        $attributes = [
          'reference' => 12345678,
          'amount' => 100000,
          'description' => 'description',
        ];
        $payment = $repository->addWithArray($attributes);
        $this->entityManager->flush();
        $id = $payment->getId();

        $this->assertTrue(!!$id);
    }

    public function testCanGetAllPayments()
    {
        $this->paymentFactory->createMany(10);
        $repository = new PaymentRepository($this->entityManager);
        $payments = $repository->getAll();
        $paymentsCount = count($payments);

        $this->assertTrue($paymentsCount === 10);
    }

    public function testCanFindPayments()
    {
        $description = 'something';
        for ($i=0; $i < 10; $i++) {
          $this->paymentFactory->create(['description' => $description]);
        }
        $repository = new PaymentRepository($this->entityManager);
        $payments = $repository->find(['description' => $description]);
        $paymentsCount = count($payments);

        $this->assertTrue($paymentsCount === 10);
    }

    public function testCanFindByDescriptionPayments()
    {
        $description = 'something';
        for ($i=0; $i < 10; $i++) {
          $this->paymentFactory->create(['description' => $description]);
        }
        $repository = new PaymentRepository($this->entityManager);
        $payments = $repository->findByDescription($description);
        $paymentsCount = count($payments);

        $this->assertTrue($paymentsCount === 10);
    }

    public function testCanGetPayment()
    {
        $entity = $this->paymentFactory->create();
        $id = $entity->getId();
        $repository = new PaymentRepository($this->entityManager);
        $payment = $repository->get($id);
        $this->assertTrue($payment->getId() === $id);
    }

    public function testCanRemovePayment()
    {
        $this->expectException(EntityNotFoundException::class);
        try {
            $entity = $this->paymentFactory->create();
            $id = $entity->getId();
            $repository = new PaymentRepository($this->entityManager);
            $repository->remove($id);
            $this->entityManager->flush();
            $payment = $repository->get($id);
        } catch (EntityNotFoundException $exception) {
            $this->assertStringContainsString(
                'Entity not found id: ' . $id,
                $exception->getMessage()
            );
            throw $exception;
        }
    }

    public function testExceptionAddWithArrayPayment()
    {
        $this->expectException(RepositoryException::class);
        try {
            $repository = new PaymentRepository($this->entityManager);
            $attributes = [
                'wrongAttr1' => 'xxxx',
                'wrongAttr2' => 'xxxxx',
            ];
            $payment = $repository->addWithArray($attributes);
        } catch (RepositoryException $exception) {
            $this->assertStringContainsString(
                'Undefined index',
                $exception->getMessage()
            );
            throw $exception;
        }
    }

    public function testExceptionAddPayment()
    {
        $this->expectException(RepositoryException::class);
        try {
            $repository = new PaymentRepository($this->entityManager);
            $dummy = new DummyEntity();
            $payment = $repository->add($dummy);
        } catch (RepositoryException $exception) {
            $this->assertStringContainsString(
                "is not a valid entity or mapped super class",
                $exception->getMessage()
            );
            throw $exception;
        }
    }

    public function testCanThrowExceptionGetPayment()
    {
        $this->expectException(EntityNotFoundException::class);
        try {
            $id = 404;
            $repository = new PaymentRepository($this->entityManager);
            $payment = $repository->get($id);
        } catch (EntityNotFoundException $exception) {
            $this->assertStringContainsString(
                'Entity not found id: ' . $id,
                $exception->getMessage()
            );
            throw $exception;
        }
    }

    public function testExceptionRemovePayment()
    {
        $this->expectException(RepositoryException::class);
        try {
            $id = 0;
            $repository = new PaymentRepository($this->entityManager);
            $payment = $repository->remove($id);
        } catch (RepositoryException $exception) {
            $this->assertStringContainsString(
                "Entity not found id",
                $exception->getMessage()
            );
            $previusException = $exception->getPrior();
            $ExceptionClass = get_class($previusException);
            $this->assertEquals($ExceptionClass, EntityNotFoundException::class);
            throw $exception;
        }
    }
}
