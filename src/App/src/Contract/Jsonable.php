<?php

namespace App\Contract;

interface Jsonable
{
    public function toJson(int $options = 0);
}
