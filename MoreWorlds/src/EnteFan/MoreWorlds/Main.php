<?php

declare(strict_types=1);

namespace EnteFan\MoreWorlds;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\level\generator\GeneratorManager;
use EnteFan\MoreWorlds\mapmanagement;


class Main extends PluginBase{

  public function onEnable(){
    $this->getServer()->getCommandMap()->register("map", new mapmanagement($this));
  }
}  
