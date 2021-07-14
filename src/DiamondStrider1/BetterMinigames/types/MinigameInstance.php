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

use pocketmine\Player;

interface MinigameInstance
{
    const STARTING = 0;
    const PLAYING = 1;
    const ENDING = 2;

    public function sendPlayer(Player $player): bool;
    public function removePlayer(Player $player): bool;
    public function sendSpectator(Player $player): bool;
    public function removeSpectator(Player $player): bool;

    /** @return int One of (STARTING, PLAYING, ENDING) */
    public function getRunningState(): int;
}
