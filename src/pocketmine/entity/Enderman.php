<?php


namespace pocketmine\entity;
use pocketmine\network\Network;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\Player;

use pocketmine\inventory\InventoryHolder;

class Enderman extends Monster implements InventoryHolder{
	const NETWORK_ID = 38;
	
	public $width = 0.6;
	public $height = 2.9;

	protected function initEntity(){
		$this->setMaxHealth(40);
		parent::initEntity();
	}

	public function getName(){
		return "Enderman"; //Aw man
	}
	
	public function getInventory(){} //TODO inventory
	
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
