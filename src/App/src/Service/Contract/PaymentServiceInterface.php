<?php

namespace App\Service\Contract;

use App\Entity\Contract\Entity;

interface PaymentServiceInterface
{
    public function create($attributes): Entity;
    public function details($id): Entity;
    public function list(): array;
    public function update($id, $attributes): Entity;
    public function destroy($id): Entity;
}
