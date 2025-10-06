<?php


namespace pocketmine\entity;
use pocketmine\network\Network;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\Player;
use pocketmine\level\format\FullChunk;
use pocketmine\nbt\tag\CompoundTag;

class Bat extends Animal{ //TODO class Neutral
	const NETWORK_ID = 19;
	
	public $width = 0.25; //todo vanilla?
	public $height = 0.25;
	
	public function __construct(FullChunk $chunk, CompoundTag $nbt){
		$this->setMaxHealth(6);
		parent::__construct($chunk, $nbt);
	}
	
	public function getName(){
		return "Bat";
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
