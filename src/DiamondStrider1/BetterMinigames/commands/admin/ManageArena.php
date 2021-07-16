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
use DiamondStrider1\BetterMinigames\utils\Utils;
use pocketmine\command\CommandSender;

class ManageArena implements Subcommand
{
    public function execute(CommandSender $sender, array $args): bool
    {
        if (count($args) < 1) return false;
        $plugin = BMG::getInstance();
        switch ($args[0]) {
            case "create":
                break;
            case "remove":
                break;
            case "list":
                $arenas = $plugin->getAllArenas();
                $cArenas = count($arenas);
                if ($cArenas == 1)
                    Utils::sendMessage($sender, "There is a single loaded arena:");
                else
                    Utils::sendMessage($sender, "There are $cArenas loaded arenas:");
                foreach ($arenas as $name => $arena) {
                    Utils::sendMessage($sender, sprintf("%s (%s)", $name, $arena->getType()));
                }
                break;
            default:
                return false;
        }

        return true;
    }

    public function getUsage(): array
    {
        return [
            "create <name>",
            "remove <name>",
            "list"
        ];
    }
}
