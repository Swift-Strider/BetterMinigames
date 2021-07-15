# BetterMinigames

*A plugin for Pocketmine which adds support for various minigames.*

## 🎮 Features
You can make several customizable minigames for your pocketmine server that is accessible through commands, forms, and both. You may choose to make temporary levels for when ever a game starts or have in lobby arenas that stay in the world they were built in.

## 📖 Terminology for BetterMinigames
* **Minigame:** A base minigame type that arenas build upon. Not to be confused with arenas, which are built by admins, these are predefined types made in code. *(ex. SkyWars, Bedwars, or Duels)*
* **Arena:** Holds a reference to a level as well as extra metadata for the minigame it's assigned to. *(ex. An arena for bedwars would keep track of it's map, and it may have metadata defined that doubles the resources dropped)*
* **Meta (or `ArenaMeta`):** Data that's specific to a Minigame. Customizable options will vary between different Minigame types.
* **Game (WIP):** Used in the `/play` command (WIP as well). This holds multiple arenas, and will refer to them as maps. (Eg. One game to hold solo skywars maps another for doubles)
* **MinigameInstance:** A running instance of an arena. These could be spawned via `/play` (WIP), or through `/duel` (WIP again).

## ❓ How to Use
(WIP)

## ❗ Tips
Be creative...
...What I mean is, for example, if you'd like to make a spleef minigame for your server, but `Spleef` isn't an avaiblable minigame type start by choosing something that closely resemble spleef, like `Duels`. After that you can force a kit that gives players shovels and snowballs, and have players die when they when they sink too low.
