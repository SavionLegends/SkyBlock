<?php

namespace SkyBlock;

use pocketmine\scheduler\Task;

class PluginHeartbeat extends Task {

    /** @var int */
    private $nextUpdate = 0;

    private $plugin;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }

    /**
     * @return Main
     */
    public function getPlugin(): Main{
        return $this->plugin;
    }

    public function onRun($currentTick) {
        $this->nextUpdate++;
        if($this->nextUpdate === 120) {
            $this->nextUpdate = 0;
            $this->getPlugin()->getIslandManager()->update();
        }
        $this->getPlugin()->getInvitationHandler()->tick();
        $this->getPlugin()->getResetHandler()->tick();
    }

}