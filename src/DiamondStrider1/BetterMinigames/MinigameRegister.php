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

namespace DiamondStrider1\BetterMinigames;

use DiamondStrider1\BetterMinigames\minigames\skywars\SkyWars;
use DiamondStrider1\BetterMinigames\types\Minigame;

class MinigameRegister
{
    /** @var Minigame[] $minigames */
    private static $minigames = [];

    public static function registerDefaultMinigames()
    {
        self::registerMinigame("SkyWars", new SkyWars);
    }

    public static function registerMinigame(string $uniqueName, Minigame $minigame): void
    {
        self::$minigames[$uniqueName] = $minigame;
    }

    /** @return Minigame[] */
    public static function getAllMinigames(): array
    {
        return self::$minigames;
    }

    /** @return Minigame */
    public static function getMinigame(string $name): ?Minigame
    {
        if (!isset(self::$minigames[$name])) {
            return null;
        }
        return self::$minigames[$name];
    }
}
