<?php


namespace pocketmine\entity;


use pocketmine\block\Block;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\Timings;
use pocketmine\item\Item as ItemItem;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\network\Network;
use pocketmine\network\protocol\EntityEventPacket;
use pocketmine\Server;
use pocketmine\utils\BlockIterator;

abstract class Living extends Entity implements Damageable{

	protected $gravity = 0.08;
	protected $drag = 0.02;

	protected $attackTime = 0;
	
	protected $invisible = \false;

	protected function initEntity(){
		parent::initEntity();

		if(isset($this->namedtag->HealF)){
			$this->namedtag->Health = new ShortTag("Health", (int) $this->namedtag["HealF"]);
			unset($this->namedtag->HealF);
		}

		if(!isset($this->namedtag->Health) or !($this->namedtag->Health instanceof ShortTag)){
			$this->namedtag->Health = new ShortTag("Health", $this->getMaxHealth());
		}

		$this->setHealth($this->namedtag["Health"]);
		if(!($this instanceof Human)) $this->setDataProperty(Entity::DATA_NO_AI, Entity::DATA_TYPE_BYTE, 1); //force stop client from updating the entity
	}

	public function setHealth($amount){
		$wasAlive = $this->isAlive();
		parent::setHealth($amount);
		if($this->isAlive() and !$wasAlive){
			$pk = new EntityEventPacket();
			$pk->eid = $this->getId();
			$pk->event = EntityEventPacket::RESPAWN;
			Server::broadcastPacket($this->hasSpawned, $pk->setChannel(Network::CHANNEL_WORLD_EVENTS));
		}
	}

	public function saveNBT(){
		parent::saveNBT();
		$this->namedtag->Health = new ShortTag("Health", $this->getHealth());
	}

	public abstract function getName();

	public function hasLineOfSight(Entity $entity){
		//TODO: head height
		return \true;
		//return $this->getLevel()->rayTraceBlocks(Vector3::createVector($this->x, $this->y + $this->height, $this->z), Vector3::createVector($entity->x, $entity->y + $entity->height, $entity->z)) === null;
	}

	public function heal($amount, EntityRegainHealthEvent $source){
		parent::heal($amount, $source);
		if($source->isCancelled()){
			return;
		}

		$this->attackTime = 0;
	}

	public function attack($damage, EntityDamageEvent $source){
		if($this->attackTime > 0 or $this->noDamageTicks > 0){
			$lastCause = $this->getLastDamageCause();
			if($lastCause !== \null and $lastCause->getDamage() >= $damage){
				$source->setCancelled();
			}
		}

		parent::attack($damage, $source);

		if($source->isCancelled()){
			return;
		}

		if($source instanceof EntityDamageByEntityEvent){
			$e = $source->getDamager();
			if($source instanceof EntityDamageByChildEntityEvent){
				$e = $source->getChild();
			}

			if($e->isOnFire() > 0){
				$this->setOnFire(2 * $this->server->getDifficulty());
			}

			$d = $e->x - $this->x;
			for ($d1 = $e->z - $this->z; $d * $d + $d1 * $d1 < 0.0001; $d1 = (lcg_value() - lcg_value()) * 0.01) {
				$d = (lcg_value() - lcg_value()) * 0.01;
			}
			$this->knockBack($d, $d1);
		}

		$pk = new EntityEventPacket();
		$pk->eid = $this->getId();
		$pk->event = $this->getHealth() <= 0 ? EntityEventPacket::DEATH_ANIMATION : EntityEventPacket::HURT_ANIMATION; //Ouch!
		Server::broadcastPacket($this->hasSpawned, $pk->setChannel(Network::CHANNEL_WORLD_EVENTS));

		$this->attackTime = 10; //0.5 seconds cooldown
	}

	public function knockBack($d, $d1){
		$f = sqrt($d * $d + $d1 * $d1);
		$f1 = 0.4;

		$motion = new Vector3($this->motionX, $this->motionY, $this->motionZ);
		
		$motion->x /= 2;
		$motion->z /= 2;
		$motion->x -= ($d / (double) $f) * (double) $f1;
		$motion->y += 0.4;
		$motion->z -= ($d1 / (double) $f) * (double) $f1;

		if($motion->y > 0.4){
			$motion->y = 0.4;
		}

		$this->setMotion($motion);
	}
	
	protected function updateMovement(){
		if($this instanceof WaterAnimal) {
			parent::updateMovement();
			return;
		}
		if($this->isAlive()){
			//echo "oG:{$this->onGround}\n";
			$this->motionY -= $this->gravity;
			$this->move($this->motionX, $this->motionY, $this->motionZ);
			$friction = 0.91;
			if($this->onGround){
				$friction = 0.54;
				$b = $this->level->getBlock(new Vector3(floor($this->x), floor($this->boundingBox->minY) - 1, floor($this->z)));
				if($b->getId() > 0) $friction = $b->slipperiness * 0.91;
			}

			$this->motionX *= $friction;
			$this->motionY *= 0.98;
			$this->motionZ *= $friction;
			
			parent::updateMovement();
		}
	}
	
	public function kill(){
		if(!$this->isAlive()){
			return;
		}
		parent::kill();
		$this->server->getPluginManager()->callEvent($ev = new EntityDeathEvent($this, $this->getDrops()));
		foreach($ev->getDrops() as $item){
			$this->getLevel()->dropItem($this, $item);
		}
	}

	public function entityBaseTick($tickDiff = 1){
		Timings::$timerLivingEntityBaseTick->startTiming();

		$hasUpdate = parent::entityBaseTick($tickDiff);

		if($this->isAlive()){
			if($this->isInsideOfSolid()){
				$hasUpdate = \true;
				$ev = new EntityDamageEvent($this, EntityDamageEvent::CAUSE_SUFFOCATION, 1);
				$this->attack($ev->getFinalDamage(), $ev);
			}

			if(!$this->hasEffect(Effect::WATER_BREATHING) and $this->isInsideOfWater()){
				if($this instanceof WaterAnimal){
					$this->setDataProperty(self::DATA_AIR, self::DATA_TYPE_SHORT, 300);
				}else{
					$hasUpdate = \true;
					$airTicks = $this->getDataProperty(self::DATA_AIR) - $tickDiff;
					if($airTicks <= -20){
						$airTicks = 0;

						$ev = new EntityDamageEvent($this, EntityDamageEvent::CAUSE_DROWNING, 2);
						$this->attack($ev->getFinalDamage(), $ev);
					}
					$this->setDataProperty(self::DATA_AIR, self::DATA_TYPE_SHORT, $airTicks);
				}
			}else{
				if($this instanceof WaterAnimal){
					$hasUpdate = \true;
					$airTicks = $this->getDataProperty(self::DATA_AIR) - $tickDiff;
					if($airTicks <= -20){
						$airTicks = 0;

						$ev = new EntityDamageEvent($this, EntityDamageEvent::CAUSE_SUFFOCATION, 2);
						$this->attack($ev->getFinalDamage(), $ev);
					}
					$this->setDataProperty(self::DATA_AIR, self::DATA_TYPE_SHORT, $airTicks);
				}else{
					$this->setDataProperty(self::DATA_AIR, self::DATA_TYPE_SHORT, 300);
				}
			}
		}
	
		$this->updateCounters($tickDiff);
		
		Timings::$timerLivingEntityBaseTick->stopTiming();

		return $hasUpdate;
	}
	public function updateCounters($tickDiff){
		if($this->attackTime > 0){
			$this->attackTime -= $tickDiff;
		}
	}
	/**
	 * @return ItemItem[]
	 */
	public function getDrops(){
		return [];
	}

	/**
	 * @param int   $maxDistance
	 * @param int   $maxLength
	 * @param array $transparent
	 *
	 * @return Block[]
	 */
	public function getLineOfSight($maxDistance, $maxLength = 0, array $transparent = []){
		if($maxDistance > 120){
			$maxDistance = 120;
		}

		if(\count($transparent) === 0){
			$transparent = \null;
		}

		$blocks = [];
		$nextIndex = 0;

		$itr = new BlockIterator($this->level, $this->getPosition(), $this->getDirectionVector(), $this->getEyeHeight(), $maxDistance);

		while($itr->valid()){
			$itr->next();
			$block = $itr->current();
			$blocks[$nextIndex++] = $block;

			if($maxLength !== 0 and \count($blocks) > $maxLength){
				\array_shift($blocks);
				--$nextIndex;
			}

			$id = $block->getId();

			if($transparent === \null){
				if($id !== 0){
					break;
				}
			}else{
				if(!isset($transparent[$id])){
					break;
				}
			}
		}

		return $blocks;
	}

	/**
	 * @param int   $maxDistance
	 * @param array $transparent
	 *
	 * @return Block
	 */
	public function getTargetBlock($maxDistance, array $transparent = []){
		try{
			$block = $this->getLineOfSight($maxDistance, 1, $transparent)[0];
			if($block instanceof Block){
				return $block;
			}
		}catch (\ArrayOutOfBoundsException $e){

		}

		return \null;
	}
}
