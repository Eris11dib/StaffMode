<?php


namespace Eris11dib\StaffMode\commands;


use Eris11dib\StaffMode\StaffMain;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class ClearInventoryCommand extends Command implements PluginIdentifiableCommand{

	private $main;

	public function __construct(StaffMain $main){
		parent::__construct('clearinventory', 'clear another player inventory','/ci (Player)', ['ci']);
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
		if($sender->hasPermission('staff.clearinventory')){
			if($sender instanceof Player) {
				if (isset($args[0])) {
					$player2 = $this->main->getServer()->getPlayerExact($args[0]);
					if ($player2 !== null) {
						$player2->getInventory()->clearAll();
						$player2->getArmorInventory()->clearAll();
						$sender->sendMessage($this->main->prefix . $this->main->getConfig()->get('player_clearinventory'));
					} else {
						$sender->sendMessage($this->main->prefix . $this->main->getConfig()->get('player_not_online'));
					}
				}else{
						$sender->getInventory()->clearAll();
						$sender->getArmorInventory()->clearAll();
				}
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