<?php

namespace solo\bossbarannounce\task;

use solo\bossbarannounce\BossBarAnnounceTask;
use xenialdan\BossBarAPI\API;

class AnnounceTask extends BossBarAnnounceTask{

  public function _onRun(int $currentTick){
    $announce = $this->owner->getNextAnnounce();
	$players = $this->owner->getServer()->getOnlinePlayers();
	if (empty($players)){
		return;
	}
    if($announce !== null){
      $prefix = $this->owner->getAnnouncePrefix();
      if($prefix !== "" && substr($prefix, -1) !== " "){
        $prefix .= " ";
      }
      $eid = API::addBossBar($players, $prefix . $announce);
      
      $this->owner->getServer()->getScheduler()->scheduleDelayedTask(new RemoveTask($this->owner, $eid), 20 * $this->owner->getInstance()->getAnnounceInterval());
    }
  }
}
