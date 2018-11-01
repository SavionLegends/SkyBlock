<?php
/**
 * Created by PhpStorm.
 * User: 20deavaults
 * Date: 11/1/18
 * Time: 8:38 AM
 */

namespace SkyBlock\commands;

use pocketmine\command\CommandSender;
use pocketmine\Player;
use SkyBlock\Main;

class SkyBlockMainCommand extends SkyBlockCommand{

    private $plugin;

    public function __construct(Main $plugin, string $name, ?string $desc, $usage, array $aliases = []){
        parent::__construct($plugin, $name, $desc, $usage, $aliases);
        $this->plugin = $plugin;
    }

    public function getPlugin(){
        return $this->plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if($sender instanceof Player){
            $playerFile = $this->getPlugin()->getSkyBlockManager()->getPlayerConfig($sender);
            if(isset($args[0]) && strtolower($args[0]) === "create"){
                if(is_null($playerFile->get("island"))){
                    $this->getPlugin()->getSkyBlockManager()->generateIsland($sender, "basic");
                    $sender->sendMessage(Main::getMessage("ISLAND_MADE"));
                }
            }
        }else{
            $sender->sendMessage(Main::getMessage("MUST_BE_IN_SERVER"));
        }
    }
}