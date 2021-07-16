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

    /** @var array $invalidEntries */
    private $invalidEntries = [];

    /** @var IDataProvider $config */
    private $config;

    public function __construct(IDataProvider $config)
    {
        $this->config = $config;
    }

    public function createArena(string $id): Arena
    {
        return $this->arenas[$id] = new Arena($id);
    }

    public function removeArena(string $id): void
    {
        unset($this->arenas[$id]);
    }

    public function getArena(string $id): ?Arena
    {
        return isset($this->arenas[$id]) ? $this->arenas[$id] : null;
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

        $ret = new DeserializationResult;
        if ($this->config->wasCorrupted()) {
            $ret->addError("Corrupted Configuration: " . $this->config->getLastError());
        }

        $result = $this->loadFromArray($this->config->getAll());
        foreach ($result->getErrors() as $e)
            $ret->addError($e);
        foreach ($result->getWarnings() as $w)
            $ret->addWarning($w);

        return $ret;
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
        foreach ($entries as $id => $arenaData) {
            if (!is_array($arenaData)) {
                $ret->addError("$id: " . TF::YELLOW . "ArenaData was not an array");
                $this->invalidEntries[$id] = $arenaData;
                continue;
            }
            $arena = new Arena($id);
            $result = $arena->loadFromArray($arenaData);
            $ret->addResult(
                $result,
                sprintf("%s (%d errors): %s", $id, count($result->getErrors()), TF::YELLOW),
                sprintf("%s (%d warnings): %s", $id, count($result->getWarnings()), TF::YELLOW),
                TF::RESET . ", " . TF::YELLOW
            );
            if ($result->hasErrors()) {
                $this->invalidEntries[$id] = $arenaData;
                continue;
            }
            $this->arenas[$id] = $arena;
        }
        return $ret;
    }

    private function saveToArray(): array
    {
        $data = $this->invalidEntries;
        foreach ($this->arenas as $id => $entry) {
            $data[$id] = $entry->saveToArray();
        }
        return $data;
    }
}
