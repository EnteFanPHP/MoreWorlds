<?php

namespace EnteFan\MoreWorlds;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\level\generator\GeneratorManager;

use EnteFan\MoreWorlds\Main;

class mapmanagement extends Command{
  
  /*@var Main*/
  private $main;
  
  public function __construct(Main $main){
    parent::__construct("map");
    $this->main = $main;
  }
  
  public function execute(CommandSender $sender, string $string, array $args){
    if(!$sender instanceof Player)return $sender->sendMessage("Versuche es in-game");
    if($sender->isOp()){
      if(empty($args[0])){
        $sender->sendMessage("§cBenutze: /map <create>, <tp/teleport {map name}> <load>");
      }elseif($args[0] == "create"){
        $this->mapCreateGui($sender);
      }elseif($args[0] == "tp" or $args[0] == "teleport"){
        if(empty($args[1])){
          $sender->sendMessage("§cBenutze: /map <create> <tp/teleport {map name}> <load>");
        }else{
          $map_name = $args[1];
          if($this->main->getServer()->isLevelGenerated($map_name)){
            $this->main->getServer()->loadLevel($map_name);
            $sender->teleport($this->main->getServer()->getLevelByName($map_name)->getSafeSpawn());
          }
        }
      }elseif($args[0] == "load"){
         $map = $args[1];
         if($this->main->getServer()->isLevelGenerated($map)){
            $this->main->getServer()->loadLevel($map);
            $sender->sendMessage("§aWelt wurde erfolgreich geladen");
         }else{
           $sender->sendMessage("§cDiese welt existiert nicht!");
         }
     }
    }
  }
  

  
  public function mapCreateGui(Player $player){
    $api = $this->main->getServer()->getPluginManager()->getPlugin("FormAPI");  
    $form = $api->createCustomForm(function (Player $player, array $data = null){
      if($data === null){
        return true;
      }
      $name = $data[0];
      if($this->main->getServer()->isLevelGenerated($name)){
        $player->sendMessage("§cDiese Map existiert bereits!");
        return true;
      }
      
      $seed = null;
      $generator = null;
      $opts = [];
      
      if($data[1] == "0"){
        $generator = GeneratorManager::getGenerator("Normal");
        $this->main->getServer()->generateLevel($name,$seed,$generator,$opts);
        $player->sendMessage("§aWelt wurde erfolgreich generiert");
        $player->sendMessage("§e==========§6info§e==========");
        $player->sendMessage("§aWelt name: §b".$name . "\n§aWelttyp: §bNormal");
        $player->sendMessage("§e==========§6info§e==========");
        if($data[2] == true){
          $player->teleport($this->main->getServer()->getLevelByName($name)->getSafeSpawn());
        }
      }
      
      if($data[1] == "1"){
        $generator = GeneratorManager::getGenerator("Flat");
        $this->main->getServer()->generateLevel($name,$seed,$generator,$opts);
        $player->sendMessage("§aWelt wurde erfolgreich generiert");
        $player->sendMessage("§e==========§6info§e==========");
        $player->sendMessage("§aWelt name: §b".$name . "\n§aWelttyp: §bFlat");
        $player->sendMessage("§e==========§6info§e=========="); 
        if($data[2] == true){
          $player->teleport($this->main->getServer()->getLevelByName($name)->getSafeSpawn());
        }
      }
      
      if($data[1] == "2"){
        $generator = GeneratorManager::getGenerator("Void");
        $this->main->getServer()->generateLevel($name,$seed,$generator,$opts);
        $player->sendMessage("§aWelt wurde erfolgreich generiert");
        $player->sendMessage("§e==========§6info§e==========");
        $player->sendMessage("§aWelt name: §b".$name . "\n§aWelttyp: §bVoid");
        $player->sendMessage("§e==========§6info§e==========");
        if($data[2] == true){
          $player->teleport($this->main->getServer()->getLevelByName($name)->getSafeSpawn());
        }
      }
      
      return true;
    });
    $form->setTitle("Create Map");
    $form->addInput("Gib einen Namen ein");
    $maps = ["Normal", "Flach", "Leer"];
    $form->addDropDown("Wahle einen Stil", $maps);
    $form->addToggle("Welt Teleport", false);
    $form->sendToPlayer($player);
    return $form;
  }
  
}
