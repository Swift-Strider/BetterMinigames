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

use DiamondStrider1\BetterMinigames\commands\Subcommand;
use DiamondStrider1\BetterMinigames\utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as TF;

class AdminCommand implements CommandExecutor
{
    /** @var Subcommand[] $subs */
    private $subs;

    public function __construct()
    {
        $this->subs = [
            "arena" => new ManageArena,
        ];
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if (count($args) < 1) {
            $this->sendHelp($sender, $label);
            return true;
        }

        if (!isset($this->subs[$args[0]])) {
            $this->sendHelp($sender, $label);
            return true;
        }

        $cmd = $this->subs[$args[0]];
        $ret = $cmd->execute($sender, array_slice($args, 1));

        if (!$ret) {
            $this->sendUsage($sender, $label, $args[0], $cmd);
        }

        return true;
    }

    private function sendHelp(CommandSender $sender, string $label)
    {
        Utils::sendMessage($sender, "Invalid Use! Try Using: ");
        foreach ($this->subs as $name => $cmd) {
            Utils::sendMessage($sender, "/$label $name ...");
        }
    }

    private function sendUsage(CommandSender $sender, string $label, string $name, Subcommand $cmd)
    {
        foreach ($cmd->getUsage() as $usage) {
            Utils::sendMessage($sender, "/$label $name " . $usage);
        }
    }
}
