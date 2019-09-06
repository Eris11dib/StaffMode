<?php


namespace Eris11dib\StaffMode\commands;


use Eris11dib\StaffMode\StaffMain;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class TempBanCommand extends Command implements PluginIdentifiableCommand{

	private $main;

	public function __construct(StaffMain $main)
	{
		parent::__construct('tempban', 'tempban a player', '/tempban (player) (time) (reason)', []);
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
		if($sender->hasPermission('staff.tempban')){
			if(isset($args[0])){
                $player2 = $this->main->getServer()->getPlayerExact($args[0]);
                if($player2 !== null){
                    if(isset($args[1])){
                        if(is_numeric(substr($args[1],0,-1))){
                            if(isset($args[2])){
                                if($this->main->getBanStatus($player2)->isBanned() === false){
                                    $this->main->banPlayer($player2, implode(' ', array_slice($args, 2)), $args[1]);
                                    var_dump($this->main->bans);
                                    $sender->sendMessage($this->main->prefix . 'Hai bannato: ' . $args[0]. ' Motivo: ' . implode(' ', array_slice($args, 2)) . ' per: ' . $args[1]);
                                }
                            }else{
                                $sender->sendMessage($this->main->prefix . 'Non hai specificato la motivazione!');
                            }
                        }else{
                            $sender->sendMessage($this->main->prefix . 'Bisogna specificare il tempo con un valore numerico!');
                        }
                    }else{
                        $sender->sendMessage($this->main->prefix . 'Non hai specificato il tempo!');
                    }
                }else{
                    $sender->sendMessage($this->main->prefix . $this->main->getConfig()->get('player_not_online'));
                }
			}else{
				$sender->sendMessage('Non hai speificato un player!');
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