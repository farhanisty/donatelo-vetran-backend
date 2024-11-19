<?php

namespace Farhanisty\DonateloBackend\Repositories;

use Farhanisty\DonateloBackend\Entity\Menu;
use Farhanisty\Vetran\Facades\Connection\Connection;

class MenuRepositoryImpl implements MenuRepository
{
    public function getAll(): array
    {
        $connection = Connection::getInstance();
        $stmt = $connection->query('SELECT * FROM menus');
        $rawMenus = $stmt->fetchAll(\PDO::FETCH_CLASS);

        $menus = array_map(function ($menu) {
            return new Menu($menu->id, $menu->name, $menu->description, $menu->price, $menu->image_url, $menu->created_at);
        }, $rawMenus);

        return $menus;
    }

    public function getById(int $id): ?Menu
    {
        $connection = Connection::getInstance();
        $stmt = $connection->prepare('SELECT * FROM menus WHERE id=?');
        $stmt->execute([$id]);

        $menu = $stmt->fetchObject();

        if (!$menu) {
            return null;
        }

        return new Menu($menu->id, $menu->name, $menu->description, $menu->price, $menu->image_url, $menu->created_at);
    }
}
