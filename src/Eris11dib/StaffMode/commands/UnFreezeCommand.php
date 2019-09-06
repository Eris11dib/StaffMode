<?php


namespace Eris11dib\StaffMode\commands;


use Eris11dib\StaffMode\StaffMain;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class UnFreezeCommand extends Command  implements PluginIdentifiableCommand{

	private $main;

	public function __construct(StaffMain $main){
		parent::__construct('unfreeze', 'unfreeze a player', '/unfreeze (player)', []);
		$this->main = $main;
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if($sender->hasPermission('staff.unfreeze')){
			if(isset($args[0])){
				$player = $this->main->getServer()->getPlayerExact($args[0]);
				if($this->main->getServer()->getPlayerExact($args[0]) !== null){
					if($sender instanceof Player){
						if($this->main->isFreezed($player)){
							$this->main->unsetFreezed($player);
							$sender->sendMessage(str_replace('%PLAYER',$args[0],$this->main->prefix . $this->main->getConfig()->get('admin_unfreezing')));
							$player2 = $this->main->getServer()->getPlayerExact($args[0]);
							$player2->sendMessage(str_replace('%PLAYER',$sender,$this->main->prefix . $this->main->getConfig()->get('player_unfreezing')));
						}
					}
				}else{
					$sender->sendMessage($this->main->prefix . $this->main->getConfig()->get('player_not_online'));
				}
			}
		}
	}

	/**
	 * @return Plugin
	 */
	public function getPlugin(): Plugin
	{
		return $this->main;
	}
}