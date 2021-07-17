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

use DiamondStrider1\BetterMinigames\BMG;
use DiamondStrider1\BetterMinigames\ArenaTypeRegister;
use DiamondStrider1\BetterMinigames\types\ArenaMeta;
use DiamondStrider1\BetterMinigames\types\DeserializationResult;
use Exception;
use pocketmine\command\CommandSender;
use pocketmine\Server;
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

    public static function constructArenaMeta(string $type, array $data, DeserializationResult &$result = null): ?ArenaMeta
    {
        $arenaType = ArenaTypeRegister::getArenaType($type);

        if (!$arenaType) {
            if ($result) {
                $result->addError("Unregistered ArenaType: " . $type);
            }
            return null;
        };

        $meta = $arenaType->getArenaMeta();
        try {
            $meta->loadFromArray($data);
        } catch (Exception $e) {
            $result->addError("Error Loading Meta: " . $e->getMessage());
        }

        return $meta;
    }

    public static function sendMessage(CommandSender $sender, string $message)
    {
        $sender->sendMessage(BMG::PREFIX . $message);
    }

    public static function makeTempLevel(string $levelname, string $prefix): string
    {
        $levelsFolder = Server::getInstance()->getDataPath() . "worlds/";
        $srcFolder = $levelsFolder . $levelname;
        $destFolder = $levelsFolder . $prefix . "-" . bin2hex(random_bytes(4)) . "-temp";

        self::copy_files($srcFolder, $destFolder);
        return $destFolder;
    }

    private static function copy_files(string $src, string $dest)
    {
        if (is_file($dest)) unlink($dest);
        if (!is_dir($dest)) mkdir($dest);
        if ($handle = opendir($src)) {
            while (($file = readdir($handle)) !== false) {
                if (!empty(array_intersect([".", ".."], [$file]))) continue;
                $srcFile = $src . "/" . $file;
                $destFile = $dest . "/" . $file;
                if (is_dir($srcFile)) {
                    self::copy_files($srcFile, $destFile);
                    continue;
                }
                copy($srcFile, $destFile);
            }
            closedir($handle);
        }
    }
}
