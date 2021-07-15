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

use DiamondStrider1\BetterMinigames\data\IDataProvider;
use DiamondStrider1\BetterMinigames\types\Arena;
use DiamondStrider1\BetterMinigames\types\DeserializationResult;
use pocketmine\utils\TextFormat as TF;

class ArenaCache
{
    /** @var Arena[] $arenas */
    private $arenas = [];

    /** @var IDataProvider $config */
    private $config;

    public function __construct(IDataProvider $config)
    {
        $this->config = $config;
    }

    public function addArena(string $name, Arena $entry): void
    {
        $this->arenas[$name] = $entry;
    }

    public function getArena(string $name): ?Arena
    {
        return isset($this->arenas[$name]) ? $this->arenas[$name] : null;
    }

    /** @return Arena[] */
    public function getAllArenas(): array
    {
        return $this->arenas;
    }

    public function load(bool $reloadConfig = false): DeserializationResult
    {
        if ($reloadConfig) {
            $this->config->reload();
        }
        $data = $this->config->getAll();
        return $this->loadFromArray($data);
    }

    public function save(): void
    {
        $entries = $this->saveToArray();
        $this->config->setAll($entries);
        $this->config->save("Data for all arenas is stored here");
    }

    private function loadFromArray(array $entries): DeserializationResult
    {
        $ret = new DeserializationResult;
        $this->arenas = [];
        foreach ($entries as $name => $arenaData) {
            $arena = new Arena;
            $result = $arena->loadFromArray($arenaData);
            $ret->addResult(
                $result,
                sprintf("%s (%d errors): %s", $name, count($result->getErrors()), TF::YELLOW),
                sprintf("%s (%d warnings): %s", $name, count($result->getWarnings()), TF::YELLOW),
                TF::RESET . ", " . TF::YELLOW
            );
            if ($result->hasErrors()) {
                // TODO: store invalid entries in array form, so admins can fix errors after shutting down the server
                continue;
            }
            $this->arenas[$name] = $arena;
        }
        return $ret;
    }

    private function saveToArray(): array
    {
        $data = [];
        foreach ($this->arenas as $name => $entry) {
            $data[$name] = $entry->saveToArray();
        }
        return $data;
    }
}
