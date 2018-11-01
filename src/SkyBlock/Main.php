<?php

namespace SkyBlock;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use SkyBlock\chat\ChatHandler;
use SkyBlock\commands\SkyBlockCommand;
use SkyBlock\generator\SkyBlockGeneratorManager;
use SkyBlock\invitation\InvitationHandler;
use SkyBlock\island\IslandManager;
use SkyBlock\reset\ResetHandler;
use SkyBlock\skyblock\SkyBlockManager;

class Main extends PluginBase {

    const MADE_ISLAND = "MADE_ISLAND";

    /** @var Main */
    private static $object = null;

    /** @var SkyBlockGeneratorManager */
    private $skyBlockGeneratorManager;

    /** @var SkyBlockManager */
    private $skyBlockManager;

    /** @var IslandManager */
    private $islandManager;

    /** @var InvitationHandler */
    private $invitationHandler;

    /** @var ResetHandler */
    private $resetHandler;

    /** @var ChatHandler */
    private $chatHandler;

    /** @var EventListener */
    private $eventListener;

    private static $messages;

    public function onLoad() {
        if(!self::$object instanceof Main) {
            self::$object = $this;
        }
    }

    public function onEnable() {
        $this->initialize();
        $this->setSkyBlockGeneratorManager();
        $this->setSkyBlockManager();
        $this->setIslandManager();
        $this->setEventListener();
        $this->setInvitationHandler();
        $this->setChatHandler();
        $this->setResetHandler();
        $this->setPluginHeartbeat();
        SkyBlockCommand::registerAll($this, $this->getServer()->getCommandMap());
        $this->getLogger()->info("Skyblock by xXSirGamesXx, remade by SavionLegends, has been Enabled");
    }

    public function onDisable() {
        $this->getLogger()->info("Skyblock by xXSirGamesXx, remade by SavionLegends, has been Disabled");
    }

    /**
     * Return Main instance
     *
     * @return Main
     */
    public static function getInstance() {
        return self::$object;
    }

    /**
     * Return SkyBlockGeneratorManager instance
     *
     * @return SkyBlockGeneratorManager
     */
    public function getSkyBlockGeneratorManager() {
        return $this->skyBlockGeneratorManager;
    }

    /**
     * Return SkyBlockManager instance
     *
     * @return SkyBlockManager
     */
    public function getSkyBlockManager() {
        return $this->skyBlockManager;
    }

    /**
     * Return island manager
     *
     * @return IslandManager
     */
    public function getIslandManager() {
        return $this->islandManager;
    }

    /**
     * Return EventListener instance
     *
     * @return EventListener
     */
    public function getEventListener() {
        return $this->eventListener;
    }

    /**
     * Return InvitationHandler instance
     *
     * @return InvitationHandler
     */
    public function getInvitationHandler() {
        return $this->invitationHandler;
    }

    /**
     * Return ResetHandler instance
     *
     * @return ResetHandler
     */
    public function getResetHandler() {
        return $this->resetHandler;
    }

    /**
     * Return ChatHandler instance
     *
     * @return ChatHandler
     */
    public function getChatHandler() {
        return $this->chatHandler;
    }

    /**
     * Register SkyBlockGeneratorManager instance
     */
    public function setSkyBlockGeneratorManager() {
        $this->skyBlockGeneratorManager = new SkyBlockGeneratorManager($this);
    }

    /**
     * Register SkyBlockManager instance
     */
    public function setSkyBlockManager() {
        $this->skyBlockManager = new SkyBlockManager($this);
    }

    /**
     * Register IslandManager instance
     */
    public function setIslandManager() {
        $this->islandManager = new IslandManager($this);
    }

    /**
     * Register EventListener instance
     */
    public function setEventListener() {
        $this->eventListener = new EventListener($this);
    }

    /**
     * Schedule the PluginHeartbeat
     */
    public function setPluginHeartbeat() {
        $this->getScheduler()->scheduleRepeatingTask(new PluginHeartbeat($this), 20);
    }

    /**
     * Register InvitationHandler instance
     */
    public function setInvitationHandler() {
        $this->invitationHandler = new InvitationHandler($this);
    }

    /**
     * Register ResetHandler instance
     */
    public function setResetHandler() {
        $this->resetHandler = new ResetHandler();
    }

    /**
     * Register ChatHandler instance
     */
    public function setChatHandler() {
        $this->chatHandler = new ChatHandler($this);
    }

    public function initialize() {
        if(!is_dir($this->getDataFolder())){
            @mkdir($this->getDataFolder());
        }
        if(!is_dir($this->getDataFolder() . "islands")){
            @mkdir($this->getDataFolder() . "islands");
        }
        if(!is_dir($this->getDataFolder() . "users")){
            @mkdir($this->getDataFolder() . "users");
        }

        $this->saveDefaultConfig();
        $this->saveResource("messages.json");
        self::$messages = json_decode(file_get_contents($this->getDataFolder() . "messages.json"), true);
        echo var_dump(self::$messages);
    }

    public static function translateColors(string $message){
        $message = str_replace("{BLACK}", TextFormat::BLACK, $message);
        $message = str_replace("{DARK_BLUE}", TextFormat::DARK_BLUE, $message);
        $message = str_replace("{DARK_GREEN}", TextFormat::DARK_GREEN, $message);
        $message = str_replace("{DARK_AQUA}", TextFormat::DARK_AQUA, $message);
        $message = str_replace("{DARK_RED}", TextFormat::DARK_RED, $message);
        $message = str_replace("{DARK_PURPLE}", TextFormat::DARK_PURPLE, $message);
        $message = str_replace("{ORANGE}", TextFormat::GOLD, $message);
        $message = str_replace("{GRAY}", TextFormat::GRAY, $message);
        $message = str_replace("{DARK_GRAY}", TextFormat::DARK_GRAY, $message);
        $message = str_replace("{BLUE}", TextFormat::BLUE, $message);
        $message = str_replace("{GREEN}", TextFormat::GREEN, $message);
        $message = str_replace("{AQUA}", TextFormat::AQUA, $message);
        $message = str_replace("{RED}", TextFormat::RED, $message);
        $message = str_replace("{LIGHT_PURPLE}", TextFormat::LIGHT_PURPLE, $message);
        $message = str_replace("{YELLOW}", TextFormat::YELLOW, $message);
        $message = str_replace("{WHITE}", TextFormat::WHITE, $message);
        $message = str_replace("{OBFUSCATED}", TextFormat::OBFUSCATED, $message);
        $message = str_replace("{BOLD}", TextFormat::BOLD, $message);
        $message = str_replace("{STRIKETHROUGH}", TextFormat::STRIKETHROUGH, $message);
        $message = str_replace("{UNDERLINE}", TextFormat::UNDERLINE, $message);
        $message = str_replace("{ITALIC}", TextFormat::ITALIC, $message);
        $message = str_replace("{RESET}", TextFormat::RESET, $message);
        return $message;
    }

    /**
     * @param string $content
     * @param array $array
     * @return mixed|string
     */

    public static function getMessage(string $content, array $array = []){
        $a = self::$messages[$content];
        $message = self::translateColors($a);
        foreach($array as $key => $value) {
            $message = str_replace("{".$key."}", $value, $message);
        }
        return $message;
    }

}