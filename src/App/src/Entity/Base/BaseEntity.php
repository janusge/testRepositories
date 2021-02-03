<?php

namespace App\Entity\Base;

use App\Entity\Contract\Entity;
use App\Exception\JsonEncodingException;
use Doctrine\Common\Collections\ArrayCollection;

abstract class BaseEntity implements Entity
{
    public function toArray()
    {
        $attributes = get_object_vars($this);
        foreach ($attributes as $key => $attribute) {
            if ($attribute instanceof ArrayCollection) {
                $arrayElements = $attribute->toArray();
                $elements = array();
                foreach ($arrayElements as $element) {
                    array_push($elements, $element->toArray());
                }
                $attributes[$key] = $elements;
            }
            if ($attribute instanceof Entity) {
                $attributes[$key] = $attribute->toArray();
            }
        }
        return $attributes;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function toJson(int $options = 0)
    {
        $json = json_encode($this->jsonSerialize(), $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new JsonEncodingException(json_last_error_msg());
        }

        return $json;
    }
}
