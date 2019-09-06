<?php


namespace Eris11dib\StaffMode\commands;


use Eris11dib\StaffMode\StaffMain;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\plugin\Plugin;

class MuteChatCommand extends Command implements PluginIdentifiableCommand{

	private $main;

	public function __construct(StaffMain $main){
		parent::__construct('mutechat', 'mute the whole server chat', '/mutechat', []);
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
		if($sender->hasPermission('staff.mutechat')){
			if($this->main->isChatMuted() === false){
				$this->main->setMutedChat();
				$this->main->getServer()->broadcastMessage($this->main->prefix . $this->main->getConfig()->get('chat_muted'));
			}else{
				$this->main->setUnMutedChat();
				$this->main->getServer()->broadcastMessage($this->main->prefix . $this->main->getConfig()->get('chat_unmuted'));
			}
		}
	}

	/**
	 * @return Plugin
	 */
	public function getPlugin(): Plugin{
		return $this->main;
	}
}