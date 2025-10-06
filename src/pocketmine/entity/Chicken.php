<?php


namespace pocketmine\entity;
use pocketmine\item\Item as ItemItem;
use pocketmine\network\Network;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\Player;

class Chicken extends Animal{
	const NETWORK_ID = 10;
	
	public $width = 0.3;
	public $height = 0.4;
	
	protected function initEntity(){
		$this->setMaxHealth(4);
		parent::initEntity();
	}
	
	public function getName(){
		return "Chicken";
	}

	public function getDrops(){
		return [
			ItemItem::get(ItemItem::FEATHER, 0, mt_rand(0, 2)),
			ItemItem::get(ItemItem::RAW_CHICKEN, 0, 1)
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
