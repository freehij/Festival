<?php


namespace pocketmine\entity;

use pocketmine\item\Item as ItemItem;
use pocketmine\network\Network;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\Player;


class PigZombie extends Zombie{
	const NETWORK_ID = 36;

	public $width = 0.3;
	public $height = 1.95;

	public function getName(){
		return "PigZombie";
	}
	
	public function getDrops(){
		return [
			ItemItem::get(ItemItem::GOLD_INGOT, 0, mt_rand(0, 1)),
			ItemItem::get(ItemItem::FEATHER, 0, mt_rand(1, 4) == 1 ? 1 : 0), //they drops potato and other stuff like zombie D=
		];
	}
	
	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->eid = $this->getId();
		$pk->type = PigZombie::NETWORK_ID;
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
		
		if(!isset($this->hasSpawned[$player->getLoaderId()]) and isset($player->usedChunks[(\PHP_INT_SIZE === 8 ? ((($this->chunk->getX()) & 0xFFFFFFFF) << 32) | (( $this->chunk->getZ()) & 0xFFFFFFFF) : ($this->chunk->getX()) . ":" . ( $this->chunk->getZ()))])){
			$this->hasSpawned[$player->getLoaderId()] = $player;
		}
	}
}
