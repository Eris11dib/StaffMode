<?php


namespace Eris11dib\StaffMode\commands;


use Eris11dib\StaffMode\StaffMain;
use muqsit\invmenu\InvMenu;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\inventory\Inventory;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class InvseeCommand extends Command implements PluginIdentifiableCommand{

	private $main;

	public function __construct(StaffMain $main)
	{
		parent::__construct('invsee', 'see the inventory of a player', '/invsee (Player)', []);
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
		if ($sender->hasPermission('staff.invsee')){
			if(isset($args[0])){
				if($sender instanceof Player){
					if($this->main->getServer()->getPlayerExact($args[0]) !== null){
						$player2 = $this->main->getServer()->getPlayerExact($args[0]);
						$menu = InvMenu::create(InvMenu::TYPE_CHEST)
							->setName($args[0] . " Inventory")
							->setListener([$this,'onTransaction'])
							->setInventoryCloseListener(function (Player $player, Inventory $inv) use ($player2) : void{
								$player2->getInventory()->setContents($inv->getContents());
						});
						$menu->send($sender);
						$menu->getInventory()->setContents($player2->getInventory()->getContents());
					}
				}
			}
		}
	}

	public function onTransaction(Player $player, Item $itemClicked, Item $itemClickedWith, SlotChangeAction $action) : bool{
		if($player->hasPermission('staff.invsee.edit')){
			return true;
		}
		return false;
	}


	/**
	 * @return Plugin
	 */
	public function getPlugin(): Plugin
	{
		return $this->main;
	}
}