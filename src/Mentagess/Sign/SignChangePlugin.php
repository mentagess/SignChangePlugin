<?php

namespace Mentagess\Sign;

use pocketmine\block\Block;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\level\Position;
use pocketmine\plugin\PluginBase;

//Le code source n'est pas de moi.

class SignChangePlugin extends PluginBase implements Listener{

    private $Wg;
    public function onEnable(){
        $this -> getServer() -> getPluginManager() -> registerEvents($this, $this);

        if ($this->getServer()->getPluginManager()->getPlugin("WorldGuard") !== null) {
            $this->Wg = $this->getServer()->getPluginManager()->getPlugin("WorldGuard");
        } else {
            $this->getLogger()->critical("WorldGuard not found, this plugin requires WorldGuard");
        }

    }
    private function WorldGuardProtection(Position $position): bool{
        if(isset($this->Wg)) {
            $result = true;
            if (($region = $this->Wg->getRegionFromPosition($position)) !== ""){
                $result = false;
            }
            return $result;
        }
        return true;
    }
    /**
     * @priority HIGHEST
     * @param PlayerInteractEvent $event
     * @return void
     */
    public function signchangeevent(SignChangeEvent $event):void {
        $player = $event->getPlayer();
        $block = $event->getBlock();

        $position = new Position($block->x, $block->y, $block->z, $block->getLevel());
        if ($this->WorldGuardProtection($position) !== true) {
          if(!$player->hasPermission("signchange.event")) {
            $event->setCancelled(true);
          }
        }
    }
}
