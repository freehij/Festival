<?php


namespace pocketmine\entity;
use pocketmine\item\Item as ItemItem;
use pocketmine\network\Network;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\Player;

class MagmaCube extends Monster{
	const NETWORK_ID = 42;
	
	public $width = 1; //todo vanilla?
	public $height = 1;

	protected function initEntity(){
		$this->setMaxHealth(2); //TODO should depend on size
		parent::initEntity();
	}

	public function getName(){
		return "Magma Cube";
	}
	
	public function getDrops(){
		return [
			ItemItem::get(ItemItem::MAGMA_CREAM, 0, mt_rand(0, 3) == 3 ? 1 : 0), //TODO should not drop if small?
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
