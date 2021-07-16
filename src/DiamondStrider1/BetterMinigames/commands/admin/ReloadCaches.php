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
use pocketmine\permission\PermissionManager;
use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;

class ReloadCaches implements Subcommand
{
    public function execute(CommandSender $sender, array $args): bool
    {
        $plugin = BMG::getInstance();
        $plugin->getLogger()->notice(TF::YELLOW . $sender->getName() . " is reloading BetterMinigames");
        $start = microtime(true);

        $getsAlerts = false;
        if ($sender->hasPermission("better-minigames.alert")) {
            $getsAlerts = true;
            // Temporarily stops the sender from getting alerts.
            // This only applies for when $sender is a player.
            PermissionManager::getInstance()
                ->unsubscribeFromPermission("better-minigames.alert", $sender);
        }

        Utils::sendMessage($sender, TF::YELLOW . "Reloading Arena Caches from Config");
        $this->handleCacheResult($sender, BMG::getInstance()->reloadArenas(), "arenas.yml");

        Utils::sendMessage($sender, TF::YELLOW . "Reloading Game Caches from Config");
        $this->handleCacheResult($sender, BMG::getInstance()->reloadGames(), "games.yml");

        $diff = microtime(true) - $start;
        Utils::sendMessage($sender, sprintf("Reloaded BetterMinigames Plugin in %.2f seconds", $diff));

        if ($getsAlerts) {
            // Gives the player the ability to see alerts if they were able to before.
            PermissionManager::getInstance()
                ->subscribeToPermission("better-minigames.alert", $sender);
        }

        return true;
    }

    public function getUsage(): array
    {
        return [
            ""
        ];
    }

    private function handleCacheResult(CommandSender $sender, DeserializationResult $result, string $fileName)
    {
        if ($sender instanceof Player) {
            if ($result->hasErrors()) {
                Utils::sendMessage(
                    $sender,
                    TF::RED . "The file $fileName has ERRORS; Check the console for more info."
                );
            }
            if ($result->hasWarnings()) {
                Utils::sendMessage(
                    $sender,
                    TF::RED . "The file $fileName has WARNINGS; Check the console for more info."
                );
            }
        }
        BMG::getInstance()->handleCacheResult($result, $fileName);
    }
}
