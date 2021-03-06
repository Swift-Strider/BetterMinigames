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
use DiamondStrider1\BetterMinigames\types\ArenaType;
use DiamondStrider1\BetterMinigames\types\MinigameInstance;
use pocketmine\level\Level;

class SkyWars implements ArenaType
{
    public function getArenaMeta(): ArenaMeta
    {
        return new SkyWarsArenaMeta;
    }

    public function createInstance(Level $level, ArenaMeta $meta): ?MinigameInstance
    {
        return new SkyWarsMinigameInstance($level, $meta);
    }
}
