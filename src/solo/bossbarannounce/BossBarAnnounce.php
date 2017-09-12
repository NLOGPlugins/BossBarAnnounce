<?php

namespace solo\bossbarannounce;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use solo\bossbarannounce\task\AnnounceTask;
use xenialdan\BossBarAPI\API;

class BossBarAnnounce extends PluginBase{

  public static $prefix = "§l§b[BossBarAnnounce] §r§7";

  private static $instance = null;

  public function getInstance(){
    return self::$instance;
  }



  private $config;

  private $announces = [];

  private $currentIndex = 0;

  private $announceTaskHandler = null;

  public function onLoad(){
    if(self::$instance !== null){
      throw new \InvalidStateException();
    }
    self::$instance = $this;
  }

  public function onEnable(){
	if (!class_exists("\\xenialdan\\BossBarAPI\\API")) {
  		$this->getLogger()->critical("BossBarAPI 플러그인(DEVirion 기반)을 넣어주세요. 플러그인을 비활성화합니다.");
  		$this->getPluginLoader()->disablePlugin($this);
		return;
	}
  	
    @mkdir($this->getDataFolder());
    $this->saveResource("setting.yml");
    $this->config = new Config($this->getDataFolder() . "setting.yml", Config::YAML);

    if(file_exists($this->getDataFolder() . "announces.json")){
      $this->announces = json_decode(file_get_contents($this->getDataFolder() . "announces.json"), true);
    }

    foreach([
      "AnnounceAddCommand", "AnnounceListCommand", "AnnounceRemoveCommand",
      "AnnounceSetIntervalCommand", "AnnounceSetPrefixCommand"
    ] as $class){
      $class = "\\solo\\bossbarannounce\\command\\" . $class;
      $this->getServer()->getCommandMap()->register("bossbarannounce", new $class($this));
    }

    $this->announceTaskHandler = $this->getServer()->getScheduler()->scheduleRepeatingTask(new AnnounceTask($this), $this->getAnnounceInterval() * 20);
  }

  public function onDisable(){

  }

  public function getAnnounce(int $index){
    return $this->announces[$index] ?? null;
  }

  public function addAnnounce(string $announce){
    $this->announces[] = $announce;
  }

  public function removeAnnounce(int $index){
    if(isset($this->announces[$index])){
      $announce = $this->announces[$index];
      unset($this->announces[$index]);
      $this->announces = array_values($this->announces);
      return $announce;
    }
    return null;
  }

  public function getAllAnnounce(){
    return $this->announces;
  }

  public function setAnnounceInterval(int $interval){
    $this->config->set("announce-interval", $interval);

    if($this->announceTaskHandler !== null){
      $this->getServer()->getScheduler()->cancelTask($this->announceTaskHandler->getTaskId());
    }
    $this->announceTaskHandler = $this->getServer()->getScheduler()->scheduleRepeatingTask(new AnnounceTask($this), $this->getAnnounceInterval() * 20);
  }

  public function getAnnounceInterval(){
    return $this->config->get("announce-interval", 60);
  }

  public function setAnnouncePrefix(string $prefix){
    $this->config->set("announce-prefix", $prefix);
  }

  public function getAnnouncePrefix(){
    return $this->config->get("announce-prefix", "§b[공지]");
  }

  public function getNextAnnounce(){
    if(!isset($this->announces[++$this->currentIndex])){
      $this->currentIndex = 0;
    }
    return $this->announces[$this->currentIndex] ?? null;
  }

  public function save(){
    $this->config->save();
    file_put_contents($this->getDataFolder() . "announces.json", json_encode($this->announces));
  }
}
