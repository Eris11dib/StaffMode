<?php

namespace Eris11dib\StaffMode\commands;

use Eris11dib\StaffMode\StaffMain;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class VanishCommand extends Command implements PluginIdentifiableCommand{

	private $main;

	public function __construct(StaffMain $main){
		parent::__construct('vanish', 'vanish command', '/vanish', ['v']);
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
		if($sender->hasPermission('staff.vanish')){
			if($sender instanceof Player){
				if($this->main->isVanished($sender) === false){
					$this->main->setVanish($sender);
					$sender->sendMessage($this->main->prefix . $this->main->getConfig()->get('in_vanish'));
				}else{
					$this->main->removeVanish($sender);
					$sender->sendMessage($this->main->prefix . $this->main->getConfig()->get('not_in_vanish'));
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