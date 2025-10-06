<?php


namespace pocketmine\entity;
use pocketmine\item\Item as ItemItem;
use pocketmine\network\Network;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\Player;

class Cow extends Animal{
	const NETWORK_ID = 11;
	
	public $width = 0.9;
	public $height = 0.9;
	
	protected function initEntity(){
		$this->setMaxHealth(10);
		parent::initEntity();
	}

	public function getName(){
		return "Cow";
	}

	public function getDrops(){
		return [
			ItemItem::get(ItemItem::RAW_BEEF, 0, mt_rand(1, 3)),
			ItemItem::get(ItemItem::LEATHER, 0, mt_rand(0, 2))
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
