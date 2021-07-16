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
use DiamondStrider1\BetterMinigames\types\Arena;
use DiamondStrider1\BetterMinigames\types\DeserializationResult;
use DiamondStrider1\BetterMinigames\types\Game;
use pocketmine\plugin\PluginBase;

class BMG extends PluginBase
{
    /** @var ArenaCache $arenaCache */
    private $arenaCache;
    /** @var GameCache $gameCache */
    private $gameCache;

    public function onEnable()
    {
        self::$instance = $this;
        $dFolder = $this->getDataFolder();

        CommandRegister::registerCommands($this);
        MinigameRegister::registerDefaultMinigames();

        $this->arenaCache = new ArenaCache(new YamlDataProvider($dFolder . "arenas.yml"));
        $this->handleCacheResult($this->arenaCache->load(), "arenas.yml");
        $this->gameCache = new GameCache(new YamlDataProvider($dFolder . "games.yml"));
        $this->handleCacheResult($this->gameCache->load(), "games.yml");
    }

    public function onDisable()
    {
        $this->arenaCache->save();
        $this->gameCache->save();
    }

    public function handleCacheResult(DeserializationResult $result, string $fileName)
    {
        if ($result->hasErrors()) {
            $this->getLogger()->emergency("Your $fileName file has ERRORS");
            foreach ($result->getErrors() as $e)
                $this->getLogger()->emergency($e);
        }
        if ($result->hasWarnings()) {
            $this->getLogger()->emergency("Your $fileName file has WARNINGS");
            foreach ($result->getWarnings() as $e)
                $this->getLogger()->emergency($e);
        }
    }

    //
    // API
    //

    public function createArena(string $id): Arena
    {
        return $this->arenaCache->createArena($id);
    }

    public function removeArena(string $id): void
    {
        foreach ($this->gameCache->getAllGames() as $game) {
            $game->removeArena($id);
        }
        $this->arenaCache->removeArena($id);
    }

    public function getArena(string $name): ?Arena
    {
        return $this->arenaCache->getArena($name);
    }

    /** @return Arena[] */
    public function getAllArenas(): array
    {
        return $this->arenaCache->getAllArenas();
    }

    public function reloadArenas(): DeserializationResult
    {
        return $this->arenaCache->load(true);
    }

    public function createGame(string $id): Game
    {
        return $this->gameCache->createGame($id);
    }

    public function removeGame(string $id): void
    {
        $this->gameCache->removeGame($id);
    }

    public function getGame(string $name): ?Game
    {
        return $this->gameCache->getGame($name);
    }

    /** @return Game[] */
    public function getAllGames(): array
    {
        return $this->gameCache->getAllGames();
    }

    public function reloadGames(): DeserializationResult
    {
        return $this->gameCache->load(true);
    }


    /** @var BMG $instance */
    private static $instance;

    public static function getInstance(): ?BMG
    {
        return self::$instance;
    }
}
