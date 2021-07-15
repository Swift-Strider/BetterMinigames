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

class DeserializationResult
{
    /** @var string[] $errors */
    private $errors = [];
    /** @var string[] $warnings */
    private $warnings = [];

    public function addResult(self $result, string $errorString, string $warningString, string $glue = ", ")
    {
        if ($result->hasErrors())
            $this->addError($errorString . implode($glue, $result->getErrors()));
        if ($result->hasWarnings())
            $this->addWarning($warningString . implode($glue, $result->getWarnings()));
    }

    public function addError(string $message): void
    {
        $this->errors[] = $message;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function addWarning(string $message): void
    {
        $this->warnings[] = $message;
    }

    public function getWarnings(): array
    {
        return $this->warnings;
    }

    public function hasWarnings(): bool
    {
        return count($this->warnings) > 0;
    }
}
