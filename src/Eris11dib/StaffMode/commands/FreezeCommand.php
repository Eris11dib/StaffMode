<?php


namespace Eris11dib\StaffMode\commands;


use Eris11dib\StaffMode\StaffMain;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class FreezeCommand extends Command implements PluginIdentifiableCommand {

	private $main;

	public function __construct(StaffMain $main)
	{
		parent::__construct('freeze', 'freeze a player!', '/freeze (player)', []);
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
		if($sender->hasPermission('staff.freeze')){
			if(isset($args[0])){
				$player = $this->main->getServer()->getPlayerExact($args[0]);
				if($sender instanceof Player){
					if($this->main->getServer()->getPlayerExact($args[0]) !== null){
						if($this->main->isFreezed($player) === false){
							$this->main->setFreezed($player);
							$sender->sendMessage(str_replace('%PLAYER',$args[0],$this->main->prefix . $this->main->getConfig()->get('admin_freezing')));
							$player2 = $this->main->getServer()->getPlayerExact($args[0]);
							$player2->sendMessage(str_replace('%PLAYER',$sender->getName(),$this->main->prefix . $this->main->getConfig()->get('player_freezing')));
						}else{
							$sender->sendMessage($this->main->prefix . $this->main->getConfig()->get('already_freezed'));
						}
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
	public function getPlugin(): Plugin
	{
		return $this->main;
	}
}