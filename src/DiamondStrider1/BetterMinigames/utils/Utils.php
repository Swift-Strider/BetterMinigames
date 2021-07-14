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

namespace DiamondStrider1\BetterMinigames\utils;

use DiamondStrider1\BetterMinigames\MinigameRegister;
use DiamondStrider1\BetterMinigames\types\ArenaMeta;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as TF;
use ReflectionClass;
use ReflectionProperty;

class Utils
{
    const META_PROPERTY_PATTERN = "/^[A-Z|_]*$/";
    const PHPDOC_CATEGORY_PATTERN = "/@Category (\S*)/";
    public static function getArenaMetaProperties(ArenaMeta $meta, string $category = null)
    {
        $metaProperties = [];
        $rClass = new ReflectionClass($meta);
        foreach ($rClass->getProperties(ReflectionProperty::IS_PUBLIC) as $rProperty) {
            $name = $rProperty->getName();
            if (preg_match(self::META_PROPERTY_PATTERN, $name) !== 1) continue;

            if ($category !== null) {
                $doc = $rProperty->getDocComment();
                $matches = [];
                if (preg_match(self::PHPDOC_CATEGORY_PATTERN, $doc, $matches) !== 1) continue;
                if (count($matches) < 2) continue;
                if ($matches[1] !== $category) continue;
            }

            $metaProperties[] = $name;
        }
        return $metaProperties;
    }

    public static function constructArenaMeta(string $type, array $data): ?ArenaMeta
    {
        $mg = MinigameRegister::getMinigame($type);
        if (!$mg) return null;
        $meta = $mg->getArenaMeta();
        $meta->loadFromArray($data);
        return $meta;
    }

    public static function sendMessage(CommandSender $sender, string $message)
    {
        $sender->sendMessage(TF::GREEN . "[BMG] " . $message);
    }
}
