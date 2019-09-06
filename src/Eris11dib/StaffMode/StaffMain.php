<?php

namespace Eris11dib\StaffMode;

use Eris11dib\StaffMode\commands\ClearInventoryCommand;
use Eris11dib\StaffMode\commands\FeedCommand;
use Eris11dib\StaffMode\commands\FlyCommand;
use Eris11dib\StaffMode\commands\FreezeCommand;
use Eris11dib\StaffMode\commands\GodCommand;
use Eris11dib\StaffMode\commands\HealCommand;
use Eris11dib\StaffMode\commands\InvseeCommand;
use Eris11dib\StaffMode\commands\MuteChatCommand;
use Eris11dib\StaffMode\commands\StaffModeCommand;
use Eris11dib\StaffMode\commands\TempBanCommand;
use Eris11dib\StaffMode\commands\TpHereCommand;
use Eris11dib\StaffMode\commands\UnBanCommand;
use Eris11dib\StaffMode\commands\UnFreezeCommand;
use Eris11dib\StaffMode\commands\VanishCommand;
use Eris11dib\StaffMode\StaffListener;
use Eris11dib\StaffMode\util\BanStatus;
use muqsit\invmenu\InvMenuHandler;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\item\Item;
use pocketmine\utils\TextFormat;
use jojoe77777\FormAPI\FormAPI;

class StaffMain extends PluginBase implements Listener
{

	/** @var string $prefix */
	public $prefix = '';

	/** @var Config */
	public $config;

	/** @var array $vanish */
	public $vanish = [];

	/** @var string $mutechat */
	public $mutechat = false;

	/** @var array $freeze */
	public $freeze = [];

	/** @var array $god */
	public $god = [];

	/** @var array $fly */
	public $fly = [];

	/** @var array $bans */
	public $bans = [];
        
    /** @var array $staffmode */
    public $staffmode = [];
        
    public $smconfig;
    
    /** @var FormAPI */
    public $formapi;
        
	public function onEnable(){
		$this->saveDefaultConfig();
		$this->prefix = $this->getConfig()->get('prefix');
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->info($this->prefix . 'Activated');
		$this->formapi = $this->getServer()->getPluginManager()->getPlugin('FormAPI');
		$this->smconfig = new Config($this->getDataFolder() . 'smInventories.yml', Config::YAML, []);
		$this->getServer()->getPluginManager()->registerEvents(new StaffListener($this),$this);
		$this->getServer()->getCommandMap()->register('vanish', new VanishCommand($this));
		$this->getServer()->getCommandMap()->register('mutechat', new MuteChatCommand($this));
		$this->getServer()->getCommandMap()->register('freeze', new FreezeCommand($this));
		$this->getServer()->getCommandMap()->register('unfreeze', new UnFreezeCommand($this));
		$this->getServer()->getCommandMap()->register('tphere', new TpHereCommand($this));
		$this->getServer()->getCommandMap()->register('clearinventory', new ClearInventoryCommand($this));
		$this->getServer()->getCommandMap()->register('heal', new HealCommand($this));
		$this->getServer()->getCommandMap()->register('feed', new FeedCommand($this));
		$this->getServer()->getCommandMap()->register('invsee', new InvseeCommand($this));
		$this->getServer()->getCommandMap()->register('fly', new FlyCommand($this));
		$this->getServer()->getCommandMap()->register('god', new GodCommand($this));
		$this->getServer()->getCommandMap()->register('tempban', new TempBanCommand($this));
		$this->getServer()->getCommandMap()->register('temppardon', new UnBanCommand($this));
        $this->getServer()->getCommandMap()->register('staffmode',new StaffModeCommand($this));
		if(file_exists($this->getServer()->getDataPath() . "bans.txt")){
			json_decode(file_get_contents("bans.txt"),true);
		}
		 if(!InvMenuHandler::isRegistered()){
			InvMenuHandler::register($this);
		}
	}

	/**
	 * @param Player $player
	 * @return bool
	 */

	public function isVanished(Player $player) : bool{
		if(in_array($player->getName(),$this->vanish,true)){
			return true;
		}
		return false;
	}

	/**
	 * @param Player $player
	 */

	public function setVanish(Player $player) : void{
		if($this->isVanished($player) === false){
			$this->vanish[] = $player->getName();
			foreach($this->getServer()->getOnlinePlayers() as $onlinePlayer){
				$onlinePlayer->hidePlayer($player);
			}
		}
	}

	/**
	 * @param Player $player
	 */

	public function removeVanish(Player $player){
		if($this->isVanished($player) === true){
			$key = array_search($player->getName(),$this->vanish,true);
			unset($this->vanish[$key]);
			foreach($this->getServer()->getOnlinePlayers() as $onlinePlayer){
				$onlinePlayer->showPlayer($player);
			}
		}
	}

	/**
	 * @param Player $player
	 */

	public function switchVanish(Player $player){
		if($this->isVanished($player)){
			$this->removeVanish($player);
		}else{
			$this->setVanish($player);
		}
	}

	/**
	 * @return bool
	 */

	public function isChatMuted() : bool{
		if($this->mutechat === true){
			return true;
		}
		return false;
	}

	public function setMutedChat() : void{
		if($this->isChatMuted() === false){
			$this->mutechat = true;
		}
	}

	public function setUnMutedChat() : void{
		if($this->isChatMuted()){
			$this->mutechat = false;
		}
	}

        /**
	 * @param Player $player
	 * @return bool
	 */

	public function isFreezed(Player $player) : bool{
		if(in_array($player->getName(),$this->freeze,true)){
			return true;
		}
		return false;
	}

	/**
	 * @param Player $player
	 */

	public function setFreezed(Player $player) : void{
		if($this->isFreezed($player) === false){
			$this->freeze[] = $player->getName();
		}
	}

	/**
	 * @param Player $player
	 */

	public function unsetFreezed(Player $player) : void{
		if($this->isFreezed($player)){
			$key = array_search($player->getName(),$this->freeze,true);
			unset($this->freeze[$key]);
		}
	}

	/**
	 * @param Player $player
	 */

	public function switchFreeze(Player $player) : void{
		if($this->isFreezed($player)){
			$this->unsetFreezed($player);
		}else{
			$this->setFreezed($player);
		}
	}

	/**
	 * @param Player $player
	 * @return bool
	 */

	public function isInGod(Player $player) : bool{
		if(in_array($player->getName(),$this->god,true)){
			return true;
		}
		return false;
	}

	public function setGod(Player $player) : void{
		if($this->isInGod($player) === false){
			$this->god[] = $player->getName();
		}
	}

	/**
	 * @param Player $player
	 */

	public function unsetGod(Player $player) : void{
		if($this->isInGod($player)){
			$key = array_search($player->getName(),$this->god,true);
			unset($this->god[$key]);
		}
	}

	/**
	 * @param Player $player
	 */

	public function switchGod(Player $player) : void{
		if($this->isInGod($player)){
			$this->unsetGod($player);
		}else{
			$this->setGod($player);
		}
	}

	/**
	 * @param Player $player
	 * @return bool
	 */

	public function isInFly(Player $player) : bool{
		if(in_array($player->getName(),$this->fly,true)){
			return true;
		}
		return false;
	}

	public function setFly(Player $player) : void{
		if($this->isInFly($player) === false){
			$this->fly[] = $player->getName();
		}
	}

	/**
	 * @param Player $player
	 */

	public function unsetFly(Player $player) : void{
		if($this->isInFly($player)){
			$key = array_search($player->getName(),$this->fly,true);
			unset($this->fly[$key]);
		}
	}

	/**
	 * @param Player $player
	 */

	public function switchFly(Player $player) : void{
		if($this->isInFly($player)){
			$this->unsetFly($player);
			$player->setAllowFlight(false);
		}else{
			$this->setFly($player);
			$player->setAllowFlight(true);
		}
	}
        
        public function isInStaffMode(Player $player) : bool {
            if(in_array(strtolower($player->getName()), $this->staffmode,true)){
                return true;
            }
            return false;
        }
        
        public function setStaffMode(Player $player) : void {
            $this->staffmode[] = strtolower($player->getName());
            $player->setGamemode(1);
            $this->setVanish($player);
            $player->sendMessage($this->prefix . $this->getConfig()->get('staffmode.enter'));
            foreach($player->getInventory()->getContents() as $slot => $item){
                $this->smconfig->setNested(strtolower($player->getName()).'.'.$slot,json_encode($item));
            }
            $this->smconfig->save();
            $player->getInventory()->clearAll();
            $player->getArmorInventory()->clearAll();
            $player->getInventory()->setItem(0, Item::get(Item::COMPASS,0,1)->setCustomName(TextFormat::YELLOW . "RandomTP"));
            $player->getInventory()->setItem(1, Item::get(Item::PACKED_ICE,0,1)->setCustomName(TextFormat::BLUE . 'Freeze'));
        }
        
        public function unsetStaffMode(Player $player) : void{
            if($this->isInStaffMode($player)){
                $player->getInventory()->clearAll();
                $player->getArmorInventory()->clearAll();
                $this->removeVanish($player);
                $player->setGamemode(0);
                foreach($this->smconfig->get(strtolower($player->getName())) as $slot => $item){
                    $player->getInventory()->setItem($slot, Item::jsonDeserialize(json_decode($item,true)));
                }
                $this->smconfig->remove(strtolower($player->getName()));
                $this->smconfig->save();
                $player->sendMessage($this->prefix . $this->getConfig()->get('staffmode.leave'));
                unset($this->staffmode[array_search(strtolower($player->getName()),$this->staffmode)]);
            }
        }

	/**
	 * @param Player $player
	 * @param string $reason
	 * @param $time
	 */
	public function banPlayer(Player $player, string $reason,$time) {
		$format = substr($time,-1);
		$duration = substr($time,0,-1);
		switch ($format){
			case "s":
				break;
			case "m":
				$time = $duration * 60;
				break;
			case "h":
				$time = $duration * 60 * 60;
				break;
			case "d":
				$time = $duration * 24 * 60 * 60;
				break;
			case "w":
				$time = $duration * 7 * 24 * 60 * 60;
				break;
			case "M":
				$time = $duration * 30 * 7 * 24 * 60 * 60;
				break;
		}
		$this->bans[strtolower($player->getName())] = [
		    'IP' => $player->getAddress(),
            'XUID' => $player->getXuid(),
            'UUID' => $player->getRawUniqueId(),
            'CID' => $player->getClientId(),
            'Scadenza' => time() + $time,
            'Motivo' => $reason
        ];
		$player->kick($this->prefix . $this->diff(time(), time() + $time).'. Motivo: '.$reason);
	}

	public function getBanStatus(Player $player) : BanStatus{
		foreach($this->bans as $lowerBannedName => $bannedData){
		    if(time() < $bannedData['Scadenza']){
                if(
                    $player->getAddress() === $bannedData['IP'] ||
                    $player->getXuid() === $bannedData['XUID'] ||
                    $player->getRawUniqueId() === $bannedData['UUID'] ||
                    $player->getClientId() === $bannedData['CID'] ||
                    strtolower($player->getName()) === $lowerBannedName
                ){
                    return new BanStatus($player, true, $bannedData['Scadenza'], $bannedData['Motivo']);
                }
            }else{
		        //il ban è già scaduto e si può eliminare
		        unset($this->bans[$lowerBannedName]);
            }
        }
		return new BanStatus($player, false);
	}

	public function unBan(string $playerName) : void{
		if(isset($this->bans[strtolower($playerName)])){
			unset($this->bans[strtolower($playerName)]);
		}
	}

	public function diff(int $i, int $f) : string{
		try{
			$dtF = new DateTime('@'.$i);
		}catch (Exception $e){
			$this->getLogger()->emergency('$dtF error');
			$this->getLogger()->emergency($e->getMessage());
			$this->getLogger()->emergency($e->getTraceAsString());
			return '';
		}
		try{
			$dtT = new DateTime('@'.$f);
		}catch (Exception $e){
			$this->getLogger()->emergency('$dtT error');
			$this->getLogger()->emergency($e->getMessage());
			$this->getLogger()->emergency($e->getTraceAsString());
			return '';
		}

		return $dtF->diff($dtT)->format('Sei bannato per %y anni %m mesi %d giorni %h ore %i minuti %s secondi');
	}

	public function onDisable(){
		file_put_contents($this->getServer()->getDataPath() . "bans.txt",json_encode($this->bans));
	}
}
