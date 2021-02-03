<?php

namespace App\Repository\Contract;

use App\Repository\Contract\UnitOfWork;
use App\Repository\Contract\PaymentRepositoryInterface;

interface PaymentUnitOfWorkInterface extends UnitOfWork
{
    public function getPaymentRepository(): PaymentRepositoryInterface;
}
