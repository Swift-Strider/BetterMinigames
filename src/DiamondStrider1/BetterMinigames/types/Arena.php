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

namespace DiamondStrider1\BetterMinigames\types;

use DiamondStrider1\BetterMinigames\exceptions\InvalidMetaType;
use DiamondStrider1\BetterMinigames\utils\Utils;

class Arena
{
    /** @var string $levelname */
    private $levelname;
    /** @var string $registeredType */
    private $registeredType;
    /** @var ArenaMeta $meta */
    private $meta;

    public function __construct(string $levelname = "", string $registeredType = "", ArenaMeta $meta = null)
    {
        $this->levelname = $levelname;
        $this->registeredType = $registeredType;
        $this->meta = $meta;
    }

    public function getType(): string
    {
        return $this->registeredType;
    }

    public function loadFromArray(array $data)
    {
        $this->levelname = $data["levelname"];
        $this->registeredType = $data["registered_type"];
        $this->meta = Utils::constructArenaMeta($this->registeredType, $data["meta"]);
        if (!$this->meta) {
            throw new InvalidMetaType($this->registeredType);
        }
    }

    public function saveToArray()
    {
        $data = [];

        $data["levelname"] = $this->levelname;
        $data["registered_type"] = $this->registeredType;
        $data["meta"] = $this->meta->saveToArray();

        return $data;
    }
}
