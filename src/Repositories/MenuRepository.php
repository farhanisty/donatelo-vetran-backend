<?php

namespace Farhanisty\DonateloBackend\Repositories;

use Farhanisty\DonateloBackend\Entity\Menu;

interface MenuRepository
{
    public function getAll(): array;

    public function getById(int $id): ?Menu;
}
