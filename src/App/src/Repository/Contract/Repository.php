<?php

namespace App\Repository\Contract;

use App\Entity\Contract\Entity;

interface Repository
{
    public function add(Entity $entity): Entity;
    public function addWithArray(array $attributes): Entity;
    public function get($id): Entity;
    public function getAll(): array;
    public function find($criteria, $orderBy, $limit, $offset): array;
    public function remove($id): Entity;
}
