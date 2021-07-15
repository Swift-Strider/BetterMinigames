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

namespace DiamondStrider1\BetterMinigames\commands\admin;

use DiamondStrider1\BetterMinigames\BMG;
use DiamondStrider1\BetterMinigames\commands\Subcommand;
use DiamondStrider1\BetterMinigames\types\DeserializationResult;
use DiamondStrider1\BetterMinigames\utils\Utils;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as TF;

class ReloadCaches implements Subcommand
{
    public function execute(CommandSender $sender, array $args): bool
    {
        $plugin = BMG::getInstance();
        $plugin->getLogger()->info(TF::YELLOW . $sender->getName() . " is reloading BetterMinigames");
        $start = microtime(true);

        Utils::sendMessage($sender, "Reloading Arena Caches from Config");
        $this->handleArenaCacheResult($sender, BMG::getInstance()->getArenaCache()->load(true));

        $diff = microtime(true) - $start;
        Utils::sendMessage($sender, sprintf("Reloaded BetterMinigames Plugin in %.2f seconds", $diff));

        return true;
    }

    public function getUsage(): array
    {
        return [
            ""
        ];
    }

    private function handleArenaCacheResult(CommandSender $sender, DeserializationResult $result)
    {
        $plugin = BMG::getInstance();

        if ($result->hasErrors()) {
            Utils::sendMessage(
                $sender,
                TF::RED . "The file arenas.yml has ERRORS; Check the console for more info."
            );
        }
        if ($result->hasWarnings()) {
            Utils::sendMessage(
                $sender,
                TF::RED . "The file arenas.yml has WARNINGS; Check the console for more info."
            );
        }

        $plugin->handleArenaCacheResult($result);
    }
}
