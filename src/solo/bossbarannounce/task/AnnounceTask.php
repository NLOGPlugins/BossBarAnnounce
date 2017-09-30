<?php

namespace solo\bossbarannounce\task;

use solo\bossbarannounce\BossBarAnnounceTask;
use pocketmine\Server;
use solo\bossbarannounce\api\API;

class AnnounceTask extends BossBarAnnounceTask{

  public function _onRun(int $currentTick){
  	$players = Server::getInstance()->getOnlinePlayers();
  	if (empty($players)) {
  		return;
  	}
  	
    $announce = $this->owner->getNextAnnounce();
    if($announce !== null){
      $prefix = $this->owner->getAnnouncePrefix();
      if($prefix !== "" && substr($prefix, -1) !== " "){
        $prefix .= " ";
      }
      $eid = API::addBossBar($players, $prefix . $announce);
      API::setPercentage(100, $eid);
      
      $this->owner->getServer()->getScheduler()->scheduleDelayedTask(new RemoveTask($this->owner, $eid), 20 * $this->owner->getInstance()->getAnnounceInterval());
    }
  }
}
