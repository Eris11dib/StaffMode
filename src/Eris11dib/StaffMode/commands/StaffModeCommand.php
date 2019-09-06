<?php

namespace Eris11dib\StaffMode\commands;

use Eris11dib\StaffMode\StaffMain;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\plugin\Plugin;
use pocketmine\Player;

/**
 * Description of StaffModeCommand
 *
 * @author Eris11dib
 */

class StaffModeCommand extends Command implements PluginIdentifiableCommand{
    
    private $main;
    
    
    public function __construct(StaffMain $main) {
        parent::__construct('staffmode', 'Enter staffmode', '', ['sm']);
        $this->main = $main;
        $this->setPermission("staff.staffmode");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if($this->testPermission($sender)){
            if($sender instanceof Player){
                if($this->main->isInStaffMode($sender) === false){
                    $this->main->setStaffMode($sender);
                }else{
                    $this->main->unsetStaffMode($sender);
                }
            }
        }
    }

    public function getPlugin(): Plugin {
        return $this->main;
    }

}
