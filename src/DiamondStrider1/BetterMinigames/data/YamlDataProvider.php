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

namespace DiamondStrider1\BetterMinigames\data;

use DiamondStrider1\BetterMinigames\BMG;
use ErrorException;
use pocketmine\utils\Config;

class YamlDataProvider implements IDataProvider
{
    /** @var Config $config */
    private $config;
    /** @var string $file */
    private $file;
    /** @var bool $corrupted */
    private $corrupted = false;
    /** @var string $lastErrorMessage */
    private $lastErrorMessage = "";

    public function __construct(string $file)
    {
        $this->file = $file;
        try {
            $this->config = new Config($file, Config::YAML);
        } catch (ErrorException $e) {
            $this->handleConfigError($e);
            $this->config = new Config($file, Config::YAML);
        }
    }

    public function getAll(): array
    {
        return $this->config->getAll();
    }

    public function setAll(array $data): void
    {
        $this->config->setAll($data);
    }

    const NEWLINE_PATTERN = "/\n/";
    public function save(string $comment): void
    {
        $this->config->save();
        $comment = preg_replace(self::NEWLINE_PATTERN, "\n#", $comment);
        file_put_contents($this->file, "#$comment\n" . file_get_contents($this->file));
    }

    public function reload(): void
    {
        try {
            $this->config->load($this->file, Config::YAML);
        } catch (ErrorException $e) {
            $this->handleConfigError($e);
            $this->config->load($this->file, Config::YAML);
        }
    }

    public function wasCorrupted(bool $resetCorrupted = true): bool
    {
        $corrupted = $this->corrupted;
        if ($resetCorrupted)
            $this->corrupted = false;
        return $corrupted;
    }

    public function getLastError(): string
    {
        return $this->lastErrorMessage;
    }

    private function handleConfigError(ErrorException $e)
    {
        $msg = $e->getMessage();
        $dFolder = BMG::getInstance()->getDataFolder();
        $newFile = str_replace(".yml", ".broken.yml", $this->file);
        $fileName = str_replace($dFolder, "", $this->file);
        $newFileName = str_replace($dFolder, "", $newFile);

        $this->corrupted = true;

        file_put_contents(
            $newFile,
            "### (Line Numbers may be off by at most 2 lines) $msg\n" . file_get_contents($this->file)
        );
        file_put_contents($this->file, "");
        $this->lastErrorMessage = "Temporarily moved $fileName to $newFileName for fixing. (This file may be overwritten the next time the file corrupts)";
    }
}
