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

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class BMG extends PluginBase
{
    /** @var BMG $instance */
    private static $instance;
    public static function getInstance(): ?BMG
    {
        return self::$instance;
    }

    /** @var ArenaCache $arenaCache */
    private $arenaCache;
    /** @var Config $arenaConfig */
    private $arenaConfig;

    public function onEnable()
    {
        self::$instance = $this;

        CommandRegister::registerCommands($this);
        MinigameRegister::registerDefaultMinigames();

        $this->arenaConfig = new Config($this->getDataFolder() . "arenas.yml");
        $this->arenaCache = new ArenaCache;
        $this->arenaCache->loadFromArray($this->arenaConfig->getAll());
    }

    public function onDisable()
    {
        $cacheData = $this->arenaCache->saveToArray();
        $this->arenaConfig->setAll($cacheData);
        $this->arenaConfig->save();
    }

    public function getArenaCache(): ?ArenaCache
    {
        return $this->arenaCache;
    }
}
