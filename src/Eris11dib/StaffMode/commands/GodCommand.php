<?php


namespace Eris11dib\StaffMode\commands;


use Eris11dib\StaffMode\StaffMain;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class GodCommand extends Command implements PluginIdentifiableCommand {

	private $main;

	public function __construct(StaffMain $main)
	{
		parent::__construct('god', 'set urself the god', '/god', []);
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
	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		if ($sender->hasPermission('staff.god')){
			if($sender instanceof Player){
				if($this->main->isInGod($sender) !== true){
					$this->main->setGod($sender);
				}else{
					$this->main->unsetGod($sender);
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