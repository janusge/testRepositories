<?php

namespace AppTest\Unit\Repository;

use App\Repository\PaymentUnitOfWork;
use AppTest\Unit\Repository\Base\DbTestCase;

class PaymentUnitOfWorkUnitTest extends DbTestCase
{
    public function testCanPaymentUnitOfWorkComplete()
    {
        $unitOfWork = new PaymentUnitOfWork($this->entityManager);
        $attributes = [
            'reference' => 12345678,
            'amount' => 100000,
            'description' => 'description',
        ];
        $repository = $unitOfWork->getPaymentRepository();
        $album = $repository->addWithArray($attributes);
        $unitOfWork->complete();
        $id = $album->getId();
        $this->assertTrue(!!$id);
    }
}
