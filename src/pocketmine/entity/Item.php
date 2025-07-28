<?php


namespace pocketmine\entity;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\ItemDespawnEvent;
use pocketmine\event\entity\ItemSpawnEvent;
use pocketmine\item\Item as ItemItem;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\Network;
use pocketmine\network\protocol\AddItemEntityPacket;
use pocketmine\Player;

class Item extends Entity{
	const NETWORK_ID = 64;

	protected $owner = \null;
	protected $thrower = \null;
	protected $pickupDelay = 0;
	/** @var ItemItem */
	protected $item;

	public $width = 0.25;
	public $height = 0.25;
	protected $gravity = 0.04;
	protected $drag = 0.02;

	public $canCollide = \false;

	protected function initEntity(){
		parent::initEntity();

		$this->setMaxHealth(5);
		$this->setHealth($this->namedtag["Health"]);
		if(isset($this->namedtag->Age)){
			$this->age = $this->namedtag["Age"];
		}
		if(isset($this->namedtag->PickupDelay)){
			$this->pickupDelay = $this->namedtag["PickupDelay"];
		}
		if(isset($this->namedtag->Owner)){
			$this->owner = $this->namedtag["Owner"];
		}
		if(isset($this->namedtag->Thrower)){
			$this->thrower = $this->namedtag["Thrower"];
		}
		$this->item = ItemItem::get($this->namedtag->Item["id"], $this->namedtag->Item["Damage"], $this->namedtag->Item["Count"]);


		$this->server->getPluginManager()->callEvent(new ItemSpawnEvent($this));
	}

	public function attack($damage, EntityDamageEvent $source){
		if(
			$source->getCause() === EntityDamageEvent::CAUSE_VOID or
			$source->getCause() === EntityDamageEvent::CAUSE_FIRE_TICK or
			$source->getCause() === EntityDamageEvent::CAUSE_ENTITY_EXPLOSION or
			$source->getCause() === EntityDamageEvent::CAUSE_BLOCK_EXPLOSION
		){
			parent::attack($damage, $source);
		}
	}

	public function onUpdate($currentTick){
		if($this->closed){
			return \false;
		}

		$tickDiff = $currentTick - $this->lastUpdate;
		if($tickDiff <= 0 and !$this->justCreated){
			return \true;
		}

		$this->lastUpdate = $currentTick;

		$this->timings->startTiming();

		$hasUpdate = $this->entityBaseTick($tickDiff);

		if($this->isAlive()){

			if($this->pickupDelay > -1 and $this->pickupDelay < 32767){ //Infinite delay
				$this->pickupDelay -= $tickDiff;
				if($this->pickupDelay < -1){
					$this->pickupDelay = -1;
				}
			}

			$this->motionY -= $this->gravity;

			if($this->checkObstruction($this->x, $this->y, $this->z)){
				$hasUpdate = \true;
			}

			$this->move($this->motionX, $this->motionY, $this->motionZ);

			$friction = 1 - $this->drag;

			if($this->onGround and (\abs($this->motionX) > 0.00001 or \abs($this->motionZ) > 0.00001)){
				$friction = $this->getLevel()->getBlock($this->temporalVector->setComponents((int) \floor($this->x), (int) \floor($this->y - 1), (int) \floor($this->z) - 1))->getFrictionFactor() * $friction;
			}

			$this->motionX *= $friction;
			$this->motionY *= 1 - $this->drag;
			$this->motionZ *= $friction;

			if($this->onGround){
				$this->motionY *= -0.5;
			}

			$this->updateMovement();

			if($this->age > 6000){
				$this->server->getPluginManager()->callEvent($ev = new ItemDespawnEvent($this));
				if($ev->isCancelled()){
					$this->age = 0;
				}else{
					$this->kill();
					$hasUpdate = \true;
				}
			}

		}

		$this->timings->stopTiming();

		return $hasUpdate or !$this->onGround or \abs($this->motionX) > 0.00001 or \abs($this->motionY) > 0.00001 or \abs($this->motionZ) > 0.00001;
	}

	public function saveNBT(){
		parent::saveNBT();
		$this->namedtag->Item = new CompoundTag("Item", [
			"id" => new ShortTag("id", $this->item->getId()),
			"Damage" => new ShortTag("Damage", $this->item->getDamage()),
			"Count" => new ByteTag("Count", $this->item->getCount())
		]);
		$this->namedtag->Health = new ShortTag("Health", $this->getHealth());
		$this->namedtag->Age = new ShortTag("Age", $this->age);
		$this->namedtag->PickupDelay = new ShortTag("PickupDelay", $this->pickupDelay);
		if($this->owner !== \null){
			$this->namedtag->Owner = new StringTag("Owner", $this->owner);
		}
		if($this->thrower !== \null){
			$this->namedtag->Thrower = new StringTag("Thrower", $this->thrower);
		}
	}

	/**
	 * @return ItemItem
	 */
	public function getItem(){
		return $this->item;
	}

	public function canCollideWith(Entity $entity){
		return \false;
	}

	/**
	 * @return int
	 */
	public function getPickupDelay(){
		return $this->pickupDelay;
	}

	/**
	 * @param int $delay
	 */
	public function setPickupDelay($delay){
		$this->pickupDelay = $delay;
	}

	/**
	 * @return string
	 */
	public function getOwner(){
		return $this->owner;
	}

	/**
	 * @param string $owner
	 */
	public function setOwner($owner){
		$this->owner = $owner;
	}

	/**
	 * @return string
	 */
	public function getThrower(){
		return $this->thrower;
	}

	/**
	 * @param string $thrower
	 */
	public function setThrower($thrower){
		$this->thrower = $thrower;
	}

	public function spawnTo(Player $player){
		$pk = new AddItemEntityPacket();
		$pk->eid = $this->getId();
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->speedX = $this->motionX;
		$pk->speedY = $this->motionY;
		$pk->speedZ = $this->motionZ;
		$pk->item = $this->getItem();
		$player->dataPacket($pk->setChannel(Network::CHANNEL_ENTITY_SPAWNING));

		$this->sendData($player);

		parent::spawnTo($player);
	}
}
