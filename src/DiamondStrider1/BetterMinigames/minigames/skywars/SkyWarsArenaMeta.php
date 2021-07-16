<?php

/*
 *   BetterMinigames is a plugin that adds the abillity to make gamemodes for PocketMine Servers
 *   Copyright (C) 2021  DiamondStrider
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

declare(strict_types=1);

namespace DiamondStrider1\BetterMinigames\minigames\skywars;

use DiamondStrider1\BetterMinigames\types\ArenaMeta;
use DomainException;
use pocketmine\math\Vector3;

class SkyWarsArenaMeta implements ArenaMeta
{
    private static $categories = [];
    public static function getCategories(): array
    {
        return self::$categories;
    }

    /**
     * @Category Positions
     * @var Vector3
     */
    public $SPAWN_POS;

    public function loadFromArray(array $data): void
    {
        $spawn_pos = $data["SPAWN_POS"];
        foreach ($spawn_pos as $num)
            if (!is_float($num) && !is_int($num))
                throw new DomainException("SPAWN_POS should only contain numbers");
        $this->SPAWN_POS = new Vector3($spawn_pos[0], $spawn_pos[1], $spawn_pos[2]);
    }

    public function saveToArray(): array
    {
        $data = [];

        $spawn_pos = $this->SPAWN_POS;
        $data["SPAWN_POS"] = [$spawn_pos->x, $spawn_pos->y, $spawn_pos->z];

        return $data;
    }

    public function validate(): bool
    {
        return false;
    }
}
