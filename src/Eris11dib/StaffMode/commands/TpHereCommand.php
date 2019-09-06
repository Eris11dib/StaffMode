<?php


namespace Eris11dib\StaffMode\commands;


use Eris11dib\StaffMode\StaffMain;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class TpHereCommand extends Command implements PluginIdentifiableCommand {

	private $main;

	public function __construct(StaffMain $main){
		parent::__construct('tphere', 'teleport a player to your position', '/tphere (player)', []);
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
		if($sender->hasPermission('staff.tphere')){
			if(isset($args[0])){
				if($sender instanceof Player){
					$player2 = $this->main->getServer()->getPlayerExact($args[0]);
					if($player2 !== null){
						$player2->teleport($sender);
						$sender->sendMessage(str_replace('%PLAYER',$player2,$this->main->prefix . $this->main->getConfig()->get('player_teleported')));
					}else{
						$sender->sendMessage($this->main->prefix . $this->main->getConfig()->get('player_not_online'));
					}
				}
			}else{
				$this->usageMessage;
			}
		}else{
			$sender->sendMessage($this->main->prefix . $this->main->getConfig()->get('no_perm'));
		}
	}

	/**
	 * @return Plugin
	 */
	public function getPlugin(): Plugin{
		return $this->main;
	}
}