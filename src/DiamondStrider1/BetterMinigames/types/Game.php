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

namespace DiamondStrider1\BetterMinigames\types;

use Exception;

class Game
{
    /** @var string $id */
    private $id;
    /** @var string[] $maps
     * $mapName => $arenaID */
    private $maps;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function addMap(string $mapName, string $arenaID): void
    {
        $this->maps[$mapName] = $arenaID;
    }

    public function removeMap(string $mapName): void
    {
        if (isset($this->maps[$mapName])) {
            unset($this->maps[$mapName]);
        }
    }

    public function removeArena(string $id): void
    {
        foreach ($this->maps as $mapName => $arenaID) {
            if ($arenaID === $id) {
                unset($this->maps[$mapName]);
            }
        }
    }

    public function loadFromArray(array $data): DeserializationResult
    {
        $ret = new DeserializationResult;

        try {
            $this->maps = $data["maps"];
        } catch (Exception $e) {
            $ret->addError($e->getMessage());
        }

        return $ret;
    }

    public function saveToArray()
    {
        $data = [];

        $data["maps"] = $this->maps;

        return $data;
    }
}
