<?php

namespace Farhanisty\DonateloBackend\Entity;

use Farhanisty\Vetran\Facades\Entity\PlainEntity;

class Token extends PlainEntity
{
    public function __construct(
        public int $id,
        public string $token,
        public bool $isActive,
        public string $createdAt,
        public string $updatedAt
    ) {}
}
