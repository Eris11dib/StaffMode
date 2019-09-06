<?php


namespace Eris11dib\StaffMode\commands;


use Eris11dib\StaffMode\StaffMain;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class FlyCommand extends Command implements PluginIdentifiableCommand{

	private $main;

	public function __construct(StaffMain $main)
	{
		parent::__construct('fly', 'activate the fly', '/fly', []);
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
		if($sender->hasPermission('staff.fly')){
			if($sender instanceof Player){
				if(isset($args[0])){
					$player2 = $this->main->getServer()->getPlayerExact($args[0]);
					$this->main->switchFly($player2);
				}else{
					$this->main->switchFly($sender);
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