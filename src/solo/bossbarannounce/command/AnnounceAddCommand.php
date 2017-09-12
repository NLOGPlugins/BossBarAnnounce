<?php

namespace solo\bossbarannounce\command;

use pocketmine\command\CommandSender;

use solo\bossbarannounce\BossBarAnnounceCommand;
use solo\bossbarannounce\BossBarAnnounce;

class AnnounceAddCommand extends BossBarAnnounceCommand{

  private $owner;

  public function __construct(BossBarAnnounce $owner){
    parent::__construct("공지추가", "공지를 추가합니다.", "/공지추가 <공지...>");
    $this->setPermission("sannounce.command.add");

    $this->owner = $owner;
  }

  public function _execute(CommandSender $sender, string $label, array $args) : bool{
    if(!$sender->hasPermission($this->getPermission())){
      $sender->sendMessage(BossBarAnnounce::$prefix . "이 명령을 실행할 권한이 없습니다.");
      return true;
    }
    if(empty($args)){
    	$sender->sendMessage(BossBarAnnounce::$prefix . "사용법 : " . $this->getUsage() . " - " . $this->getDescription());
      return true;
    }
    $announce = implode(" ", $args);
    $this->owner->addAnnounce($announce);
    $this->owner->save();
    $sender->sendMessage(BossBarAnnounce::$prefix . "공지를 추가하였습니다 : " . $announce);
    return true;
  }
}
