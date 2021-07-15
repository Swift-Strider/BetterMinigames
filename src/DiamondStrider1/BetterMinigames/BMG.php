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

use DiamondStrider1\BetterMinigames\data\YamlDataProvider;
use DiamondStrider1\BetterMinigames\exceptions\CacheLoadException;
use DiamondStrider1\BetterMinigames\types\DeserializationResult;
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

    public function onEnable()
    {
        self::$instance = $this;
        $dFolder = $this->getDataFolder();

        CommandRegister::registerCommands($this);
        MinigameRegister::registerDefaultMinigames();

        $this->arenaCache = new ArenaCache(new YamlDataProvider($dFolder . "arenas.yml"));
        $this->handleArenaCacheResult($this->arenaCache->load());
    }

    public function onDisable()
    {
        $this->arenaCache->save();
    }

    public function getArenaCache(): ?ArenaCache
    {
        return $this->arenaCache;
    }

    public function handleArenaCacheResult(DeserializationResult $result)
    {
        if ($result->hasErrors()) {
            $this->getLogger()->emergency("Your arenas.yml file has ERRORS");
            foreach ($result->getErrors() as $e)
                $this->getLogger()->emergency($e);
        }
        if ($result->hasWarnings()) {
            $this->getLogger()->emergency("Your arenas.yml file has WARNINGS");
            foreach ($result->getWarnings() as $e)
                $this->getLogger()->emergency($e);
        }
    }
}
