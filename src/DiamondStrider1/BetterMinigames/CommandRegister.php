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

use pocketmine\command\CommandExecutor;
use pocketmine\command\PluginCommand;
use pocketmine\Server;

class CommandRegister
{
    /** @var BMG $plugin */
    private static $plugin;
    /** @var Server $server */
    private static $server;

    public static function registerCommands(BMG $plugin)
    {
        self::$plugin = $plugin;
        self::$server = $plugin->getServer();
    }

    private static function registerCommandExecutor(string $command, CommandExecutor $executor)
    {
        $cmd = self::$server->getCommandMap()->getCommand($command);

        if (!($cmd instanceof PluginCommand)) return;
        if ($cmd->getPlugin() !== self::$plugin) return;

        $cmd->setExecutor($executor);
    }
}
