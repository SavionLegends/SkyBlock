<?php
/**
 * Created by PhpStorm.
 * User: Savion
 * Date: 4/29/2017
 * Time: 12:28 PM
 */

namespace SkyBlock\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandMap;
use pocketmine\command\CommandSender;
use SkyBlock\Main;

class SkyBlockCommand extends Command{

    private $plugin;

    /**
     * MinigamesCommand constructor.
     * @param Main $plugin
     * @param string $name
     * @param null|string $desc
     * @param array|\string[] $usage
     * @param array $aliases
     */
    public function __construct(Main $plugin, $name, $desc, $usage, $aliases = []){
        parent::__construct($name, $desc, $usage, (array)$aliases);
        $this->plugin = $plugin;
    }


    public function getPlugin(){
        return $this->plugin;
    }

    /**
     * @param Main $main
     * @param CommandMap $map
     */
    public static function registerAll(Main $main, CommandMap $map){
        $cmds = ["tell","help","me","?","msg"];
        foreach($cmds as $cmd){
            self::unregisterCommand($map, $cmd);
        }
        $map->registerAll("skyblock", [new SkyBlockMainCommand($main, "skyblock", "Skyblock command!", "/skyblock [args]")]);
    }

    /**
     * @param CommandMap $map
     * @param $name
     * @return bool
     */
    public static function unregisterCommand(CommandMap $map, $name){
        $cmd = $map->getCommand($name);
        if($cmd instanceof Command){
            $cmd->setLabel($name . "_disabled");
            $cmd->unregister($map);
            return true;
        }
        return false;
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if(parent::testPermission($sender) === false){
            return false;
        }else{
            $result = $this->onExecute($sender, $args);
            if(is_string(strtolower($result))){
                $sender->sendMessage($result);
            }
            return true;
        }
    }

    /**
     * @param CommandSender $sender
     * @param array $args
     * @return bool
     */
    public function onExecute(CommandSender $sender, array $args){
        if(parent::testPermission($sender) === false){
            return false;
        }
        return true;
    }
}