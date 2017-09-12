<?php

namespace solo\bossbarannounce\command;

use pocketmine\command\CommandSender;

use solo\bossbarannounce\BossBarAnnounceCommand;
use solo\bossbarannounce\BossBarAnnounce;

class AnnounceListCommand extends BossBarAnnounceCommand{

  private $owner;

  public function __construct(BossBarAnnounce $owner){
    parent::__construct("공지목록", "공지 목록을 확인합니다.", "/공지목록 [페이지]");
    $this->setPermission("sannounce.command.list");

    $this->owner = $owner;
  }

  public function _execute(CommandSender $sender, string $label, array $args) : bool{
    if(!$sender->hasPermission($this->getPermission())){
    	$sender->sendMessage(BossBarAnnounce::$prefix . "이 명령을 실행할 권한이 없습니다.");
      return true;
    }
    $countPerPage = 5;
    $maxPage = ceil(count($this->owner->getAllAnnounce()) / $countPerPage);
    $page = 1;
    if(!empty($args) && preg_match("/[0-9]+/", $args[0])){
      $page = intval($args[0]);
    }
    $page = max(1, min($maxPage, $page));

    $sender->sendMessage("§l==========[ 공지 목록 (전체 " . $maxPage . "페이지 중 " . $page . "페이지) ]==========§r");
    for($i = ($page - 1) * 5; $i < $page * 5; $i++){
      $announce = $this->owner->getAnnounce($i);
      if($announce === null){
        break;
      }
      $sender->sendMessage("§7[" . $i . "] " . $announce);
    }
    return true;
  }
}
