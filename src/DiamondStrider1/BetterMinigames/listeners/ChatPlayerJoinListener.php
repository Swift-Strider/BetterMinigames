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

namespace DiamondStrider1\BetterMinigames\listeners;

use DiamondStrider1\BetterMinigames\BMG;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\permission\Permission;
use pocketmine\scheduler\ClosureTask;

class ChatPlayerJoinListener implements Listener
{
    /** @var string $message */
    private $message;
    /** @var Permission $perm */
    private $perm;
    /** @var string[] $blacklist */
    private $blacklist = [];

    public function __construct(string $message, Permission $perm)
    {
        $this->message = $message;
        $this->perm = $perm;
    }

    public function onPlayerJoin(PlayerJoinEvent $ev)
    {
        $player = $ev->getPlayer();
        $sched = BMG::getInstance()->getScheduler();
        $sched->scheduleDelayedTask(new ClosureTask(function (int $currentTick) use (&$player): void {
            $seenMessage = isset($this->blacklist[$player->getUniqueId()->toString()]);
            if ($player->hasPermission($this->perm) && !$seenMessage) {
                $player->sendMessage($this->message);
                $this->blacklist[$player->getUniqueId()->toString()] = true;
            }
        }), 30);
    }
}
