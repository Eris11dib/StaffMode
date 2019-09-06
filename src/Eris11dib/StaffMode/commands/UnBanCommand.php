<?php


namespace Eris11dib\StaffMode\commands;


use Eris11dib\StaffMode\StaffMain;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\OfflinePlayer;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class UnBanCommand extends Command implements PluginIdentifiableCommand{

	private $main;

	public function __construct(StaffMain $main)
	{
		parent::__construct('temppardon', 'pardon a player', '/temppardon player', []);
		$this->main = $main;
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param array $args
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		if($sender->hasPermission('staff.unban')) {
            if (isset($args[0])) {
                if (isset($this->main->bans[strtolower($args[0])])) {
                    $this->main->unBan($args[0]);
                    $sender->sendMessage($this->main->prefix . 'Hai sbannato: ' . $args[0]);
                } else {
                    $sender->sendMessage($this->main->prefix . 'Questo player non è bannato!');
                }
            } else {
                $sender->sendMessage($this->main->prefix . 'Non hai specificato un player bannato!');
            }
		}
		return true;
	}

	/**
	 * @return Plugin
	 */
	public function getPlugin(): Plugin
	{
		return $this->main;
	}
}