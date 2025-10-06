<?php


namespace pocketmine\entity;
use pocketmine\item\Item as ItemItem;
use pocketmine\network\Network;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\Player;

class CaveSpider extends Monster{ //TODO should extend SPIDER
	const NETWORK_ID = 40;
	public $width = 0.7; //from newer pocketmine
	public $height = 0.5;

	protected function initEntity(){
		$this->setMaxHealth(12);
		parent::initEntity();
	}

	public function getName(){
		return "Cave Spider";
	}

	public function getDrops(){
		return [
			ItemItem::get(ItemItem::STRING, 0, 1)
		];
	}

	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->eid = $this->getId();
		$pk->type = self::NETWORK_ID;
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->speedX = $this->motionX;
		$pk->speedY = $this->motionY;
		$pk->speedZ = $this->motionZ;
		$pk->yaw = $this->yaw;
		$pk->pitch = $this->pitch;
		$pk->metadata = $this->dataProperties;
		$player->dataPacket($pk->setChannel(Network::CHANNEL_ENTITY_SPAWNING));

		parent::spawnTo($player);
	}
}
