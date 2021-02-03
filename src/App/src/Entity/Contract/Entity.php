<?php

namespace App\Entity\Contract;

use App\Contract\Jsonable;
use App\Contract\Arrayable;
use JsonSerializable;

interface Entity extends Jsonable, Arrayable, JsonSerializable
{
}
