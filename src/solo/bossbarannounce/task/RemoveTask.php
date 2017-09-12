<?php

namespace solo\bossbarannounce\task;

use solo\bossbarannounce\BossBarAnnounceTask;
use pocketmine\network\mcpe\protocol\RemoveEntityPacket;

class RemoveTask extends BossBarAnnounceTask{
	
	private $eid;
	
	public function __construct($owner, $eid) {
		parent::__construct($owner);
		$this->eid = $eid;
	}
	
	public function _onRun(int $currentTick){
		$pk = new RemoveEntityPacket();
		$pk->entityUniqueId = $this->eid;
		
		$this->owner->getServer()->broadcastPacket($this->owner->getServer()->getOnlinePlayers(), $pk);
	}
}
