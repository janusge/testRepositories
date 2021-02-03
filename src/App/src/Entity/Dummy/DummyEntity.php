<?php

namespace App\Entity\Dummy;

use App\Entity\Base\BaseEntity;

class DummyEntity extends BaseEntity
{
    protected $id = 0;

    public function getId()
    {
        return $this->id;
    }
}
