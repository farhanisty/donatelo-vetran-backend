<?php

namespace Farhanisty\DonateloBackend\Entity;

use Farhanisty\Vetran\Facades\Entity\PlainEntity;

class Menu extends PlainEntity
{
    public function __construct(
        public int $id,
        public string $name,
        public string $description,
        public float $price,
        public string $image,
        public string $createdAt
    ) {}
}
