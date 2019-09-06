<?php


namespace Eris11dib\StaffMode;


use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerBlockPickEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class StaffListener implements Listener {

	public $main;

	public function __construct(StaffMain $main){
		$this->main = $main;
	}

	public function onLogOut(PlayerQuitEvent $event){
		if($this->main->isVanished($event->getPlayer())){
			$this->main->removeVanish($event->getPlayer());
		}
		if($this->main->isFreezed($event->getPlayer())){
			$this->main->unsetFreezed($event->getPlayer());
		}
		if($this->main->isInGod($event->getPlayer())){
			$this->main->unsetGod($event->getPlayer());
		}
		if($this->main->isInStaffMode($event->getPlayer())){
		    $this->main->unsetStaffMode($event->getPlayer());
		}
	}

	public function onJoin(PlayerJoinEvent $event){
		foreach($this->main->getServer()->getOnlinePlayers() as $onlinePlayer){
			if($this->main->isVanished($onlinePlayer)){
				$onlinePlayer->hidePlayer($event->getPlayer());
			}
		}
	}

	public function onPreLogin(PlayerPreLoginEvent $event) : void{
        $banStatus = $this->main->getBanStatus($event->getPlayer());
        if($banStatus->isBanned()){
            $event->setKickMessage($this->main->diff($banStatus->getUntil(), time()).'. Motivo: '.$banStatus->getReason());
            $event->setCancelled();
        }
    }

	public function onAttack(EntityDamageByEntityEvent $event){
		$entity = $event->getEntity();
		$damager = $event->getDamager();
		if($entity instanceof Player){
			if($this->main->isFreezed($damager)){
				$event->setCancelled();
			}
			if($this->main->isInGod($damager)){
				$event->setCancelled();
			}
		}
		if($entity instanceof Player){
		    if($this->main->isInStaffMode($damager)){
		        if($damager instanceof Player){
		            if($damager->getInventory()->getItemInHand()->getCustomName() === TextFormat::BLUE . "Freeze"){
		                $event->setCancelled();
		                $this->main->setFreezed($entity);
		                $damager->sendMessage($this->main->prefix . "You freezed: " . $entity->getName());
		                $entity->sendMessage($this->main->prefix . "You were freezed by: " . $damager->getName());
		            }
		        }
		    }
		}
	}

	public function onPick(PlayerBlockPickEvent $event){
		if($this->main->isVanished($event->getPlayer())){
			$event->setCancelled();
		}
		if($this->main->isInStaffMode($event->getPlayer())){
		    $event->setCancelled();
		}
	}

	public function onBreak(BlockBreakEvent $event){
		if($this->main->isFreezed($event->getPlayer())){
			$event->setCancelled();
		}
		if($this->main->isInStaffMode($event->getPlayer())){
		    $event->setCancelled();
		}
	}

	public function onPlace(BlockPlaceEvent $event){
		if($this->main->isFreezed($event->getPlayer())){
			$event->setCancelled();
		}
		if($this->main->isInStaffMode($event->getPlayer())){
		    $event->setCancelled();
		}
	}

	public function onChat(PlayerChatEvent $event){
		$player = $event->getPlayer();
		if ($this->main->isChatMuted()) {
			if (!$player->hasPermission('mutechat.bypass')) {
				$event->setCancelled();
				$player->sendMessage($this->main->prefix . $this->main->getConfig()->get('cannot_chat'));
			}
		}
	}
	public function onMove(PlayerMoveEvent $event){
		$player = $event->getPlayer();
		if($this->main->isFreezed($player)){
			$event->setCancelled();
		}
	}
        
         public function onInteract(PlayerInteractEvent $event){
            $player = $event->getPlayer();
            $item = $player->getInventory()->getItemInHand();
            if($this->main->isInStaffMode($player)){
                if($item->getCustomName() === TextFormat::YELLOW . "RandomTP"){
                    $test = array_rand($this->main->getServer()->getOnlinePlayers());
                     $playerTotp = $this->main->getServer()->getOnlinePlayers()[$test];
                        if(!$playerTotp->hasPermission('randomtp.bypass')){
                            if($playerTotp !== $player){
                                $player->teleport($playerTotp);
                                $player->sendMessage($this->main->prefix . 'Teleported to: ' . $playerTotp->getName());
                        }
                    }
                }
         }
    }
}