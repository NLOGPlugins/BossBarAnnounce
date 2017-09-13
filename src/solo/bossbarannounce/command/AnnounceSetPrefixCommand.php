<?php

namespace solo\bossbarannounce\command;

use pocketmine\command\CommandSender;

use solo\bossbarannounce\BossBarAnnounce;
use solo\bossbarannounce\BossBarAnnounceCommand;

class AnnounceSetPrefixCommand extends BossBarAnnounceCommand{

  private $owner;

  public function __construct(BossBarAnnounce $owner){
    parent::__construct("공지접두사", "공지의 접두사를 설정합니다.", "/공지접두사 <접두사...>");
    $this->setPermission("bossbarannounce.command.setprefix");

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
    $prefix = implode(" ", $args);
    $this->owner->setAnnouncePrefix($prefix);
    $this->owner->save();
    $sender->sendMessage(BossBarAnnounce::$prefix . "공지의 접두사를 변경하였습니다 : " . $prefix);
    return true;
  }
}
