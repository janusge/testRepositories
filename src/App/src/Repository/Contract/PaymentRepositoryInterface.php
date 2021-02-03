<?php

namespace App\Repository\Contract;

use App\Repository\Contract\UnitOfWork;
use App\Repository\Contract\PaymentRepository;

interface PaymentRepositoryInterface
{
    public function findByDescription($description, $orderBy, $limit, $offset): array;
}
