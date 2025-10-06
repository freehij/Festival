<?php


namespace pocketmine\entity;


use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\Item as ItemItem;
use pocketmine\network\Network;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\Player;

class Zombie extends Monster{
	const NETWORK_ID = 32;

	public $width = 0.6;
	public $height = 1.8;

	protected function initEntity(){
		parent::initEntity();
		$this->setMaxHealth(20);
	}

	public function getName(){
		return "Zombie";
	}

	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->eid = $this->getId();
		$pk->type = Zombie::NETWORK_ID;
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

	public function getDrops(){
		$drops = [
			ItemItem::get(ItemItem::ROTTEN_FLESH, 0, 1)
		];
		if($this->lastDamageCause instanceof EntityDamageByEntityEvent and $this->lastDamageCause->getEntity() instanceof Player){
			if(\mt_rand(0, 199) < 5){
				switch(\mt_rand(0, 2)){
					case 0:
						$drops[] = ItemItem::get(ItemItem::IRON_INGOT, 0, 1);
						break;
					case 1:
						$drops[] = ItemItem::get(ItemItem::CARROT, 0, 1);
						break;
					case 2:
						$drops[] = ItemItem::get(ItemItem::POTATO, 0, 1);
						break;
				}
			}
		}

		return $drops;
	}
}
