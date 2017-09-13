<?php

namespace solo\bossbarannounce\command;

use pocketmine\command\CommandSender;

use solo\bossbarannounce\BossBarAnnounceCommand;
use solo\bossbarannounce\BossBarAnnounce;

class AnnounceSetIntervalCommand extends BossBarAnnounceCommand{

  private $owner;

  public function __construct(BossBarAnnounce $owner){
    parent::__construct("공지주기", "공지가 표시되는 주기를 설정합니다.", "/공지주기 <주기(단위:초)>");
    $this->setPermission("bossbarannounce.command.setinterval");

    $this->owner = $owner;
  }

  public function _execute(CommandSender $sender, string $label, array $args) : bool{
    if(!$sender->hasPermission($this->getPermission())){
    	$sender->sendMessage(BossBarAnnounce::$prefix . "이 명령을 실행할 권한이 없습니다.");
      return true;
    }
    if(empty($args) || !preg_match("/[0-9]+/", $args[0])){
    	$sender->sendMessage(BossBarAnnounce::$prefix . "사용법 : " . $this->getUsage() . " - " . $this->getDescription());
      return true;
    }
    $interval = intval($args[0]);
    $this->owner->setAnnounceInterval($interval);
    $this->owner->save();
    $sender->sendMessage(BossBarAnnounce::$prefix . "공지의 주기를 " . $interval . "초로 설정하였습니다.");
    return true;
  }
}
