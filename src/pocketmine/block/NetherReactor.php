<?php


namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\level\generator\object\NetherReactorStructure;
use pocketmine\scheduler\ReactorActivateTask;
use pocketmine\math\Vector3;
use pocketmine\entity\PigZombie;
use pocketmine\entity\Entity;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\EnumTag;
use pocketmine\nbt\tag\FloatTag;

class NetherReactor extends Solid{
	public static $enableReactor = false;
	protected $id = self::NETHER_REACTOR;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		return "Nether Reactor";
	}
	
	private function isCorrect($x, $y, $z){
		$offsetX = -1;
		$offsetZ = -1;
		foreach($this->core as $yOffset => $layer){
			foreach($layer as $line){
				foreach(str_split($line) as $char){
					$b = $this->getLevel()->getBlockIdAt($x + $offsetX, $y + $yOffset, $z + $offsetZ);
					switch($char){
						case "G":
							if($b === Block::GOLD_BLOCK){ //TODO make it use structure class
								break;
							}
							return false;
						case "C":
							if($b === Block::COBBLESTONE){
								break;
							}
							return false;
						case "R":
							if($b === Block::NETHER_REACTOR and $this->getLevel()->getBlockDataAt($x + $offsetX, $y + $yOffset, $z + $offsetZ) === 0){
								break;
							}
							return false;
						case " ":
							if($b === 0){
								break;
							}
							return false;
					}
					++$offsetX;
				}
				++$offsetZ;
				$offsetX = -1;
			}
			$offsetZ = -1;
		}
		return true;
	}
	
	public function onActivate(Item $item, Player $player = \null){
		if($this->meta == 0 && $this->isCorrect($this->x,$this->y,$this->z) && NetherReactor::$enableReactor){
			if($this->y > 101){
				//TODO send msg to a player
				return;
			}
			
			NetherReactorStructure::buildReactor($this->getLevel(), $this->x, $this->y, $this->z);
			$this->meta = 1;
			$this->getLevel()->setBlock($this,$this);
			Server::getInstance()->getScheduler()->scheduleDelayedTask(new ReactorActivateTask($this, ReactorActivateTask::TYPE_GLOW, [1]), 40);
			Server::getInstance()->getScheduler()->scheduleDelayedTask(new ReactorActivateTask($this, ReactorActivateTask::TYPE_GLOW, [2]), 60);
			Server::getInstance()->getScheduler()->scheduleDelayedTask(new ReactorActivateTask($this, ReactorActivateTask::TYPE_GLOW, [3]), 80);
			Server::getInstance()->getScheduler()->scheduleDelayedTask(new ReactorActivateTask($this, ReactorActivateTask::TYPE_GLOW, [4]), 140);
			/*
			$server->schedule(500, array($this, "spawnItems"), [17,32, "checkPigmen",false]); //500
			$server->schedule(580, array($this, "spawnItems"), [17,32, "checkPigmen",false]);
			$server->schedule(620, array($this, "spawnItems"), [1,32, "checkPigmen",false]);
			$server->schedule(660, array($this, "spawnItems"), [1,32,"checkPigmen",false]);
			$server->schedule(700, array($this, "spawnItems"), [1,32,"checkPigmen",false]);
			*/
			Server::getInstance()->getScheduler()->scheduleDelayedTask(new ReactorActivateTask($this, ReactorActivateTask::TYPE_SPAWN_ITEMS, [0, 15, 2, true]), 200);
			Server::getInstance()->getScheduler()->scheduleDelayedTask(new ReactorActivateTask($this, ReactorActivateTask::TYPE_SPAWN_ITEMS, [0, 15, "checkPigmen", true]), 260);
			Server::getInstance()->getScheduler()->scheduleDelayedTask(new ReactorActivateTask($this, ReactorActivateTask::TYPE_SPAWN_ITEMS, [0, 15, "checkPigmen", true]), 300);
			Server::getInstance()->getScheduler()->scheduleDelayedTask(new ReactorActivateTask($this, ReactorActivateTask::TYPE_SPAWN_ITEMS, [11, 20, "checkPigmen", false]), 340);
			Server::getInstance()->getScheduler()->scheduleDelayedTask(new ReactorActivateTask($this, ReactorActivateTask::TYPE_SPAWN_ITEMS, [0, 10, "checkPigmen", false]), 400);
			Server::getInstance()->getScheduler()->scheduleDelayedTask(new ReactorActivateTask($this, ReactorActivateTask::TYPE_SPAWN_ITEMS, [17, 32, "checkPigmen", false]), 500);
			Server::getInstance()->getScheduler()->scheduleDelayedTask(new ReactorActivateTask($this, ReactorActivateTask::TYPE_SPAWN_ITEMS, [17, 32, "checkPigmen", false]), 580);
			Server::getInstance()->getScheduler()->scheduleDelayedTask(new ReactorActivateTask($this, ReactorActivateTask::TYPE_SPAWN_ITEMS, [1, 32, "checkPigmen", false]), 620);
			Server::getInstance()->getScheduler()->scheduleDelayedTask(new ReactorActivateTask($this, ReactorActivateTask::TYPE_SPAWN_ITEMS, [1, 32, "checkPigmen", false]), 660);
			Server::getInstance()->getScheduler()->scheduleDelayedTask(new ReactorActivateTask($this, ReactorActivateTask::TYPE_SPAWN_ITEMS, [1, 32, "checkPigmen", false]), 700);
			
			Server::getInstance()->getScheduler()->scheduleDelayedTask(new ReactorActivateTask($this, ReactorActivateTask::TYPE_GLOW, [5]), 860);
			Server::getInstance()->getScheduler()->scheduleDelayedTask(new ReactorActivateTask($this, ReactorActivateTask::TYPE_GLOW, [6]), 880);
			Server::getInstance()->getScheduler()->scheduleDelayedTask(new ReactorActivateTask($this, ReactorActivateTask::TYPE_GLOW, [7]), 900);
			Server::getInstance()->getScheduler()->scheduleDelayedTask(new ReactorActivateTask($this, ReactorActivateTask::TYPE_DESTROY), 920);
		}else{
			//TODO send msg to a player
				return;
		}
	}
	
	private function pigmenCheck($x,$y,$z) {
		$pigCount = 0;
		foreach($this->getLevel()->getEntities() as $entity) {
			if($entity instanceof PigZombie && $entity->x < $x + 8 && $entity->x > $x - 8 && $entity->z < $z + 8 && $entity->z > $z - 8 && $entity->y > $y - 2 && $entity->y < $y + 3){
				$pigCount += 1;
			}
		}
		return $pigCount < 3 ? $pigCount < 2 ? 2 : 1 : 0;
	}
	
	public function spawnItems($data) {
		$x = $this->x;
		$y = $this->y;
		$z = $this->z;
		$minAmount = $data[0];
		$maxAmount = $data[1];
		$pigmen = $data[2] === "checkPigmen" ? $this->pigmenCheck($x, $y, $z) : $data[2];
		$forceAmount = $data[3];
		if(!$forceAmount){
			$spawnNumber = $minAmount + floor(lcg_value()*($maxAmount-$minAmount+1));
		}
		else{
			$spawnNumber = $maxAmount;
		}
		for($i = 0; $i < $spawnNumber; $i++) {
			$randomRange = floor(lcg_value()*5+3);
			$shiftX = cos(floor(lcg_value()*360)*(pi()/180));
			$shiftZ = sin(floor(lcg_value()*360)*(pi()/180));
			if(lcg_value() <= 5 / 100) $randomID = $this->rarePossibleLoot[array_rand($this->rarePossibleLoot)];
			else $randomID = $this->possibleLoot[array_rand($this->possibleLoot)];
			$this->getLevel()->dropItem(new Vector3($x+($shiftX*$randomRange)+0.5, $y, $z+($shiftZ*$randomRange)+0.5), Item::get($randomID, 0, 1));
		}
		for($i = 0; $i < $pigmen; $i++) {
			$randomRange = floor(lcg_value()*5+3);
			$shiftX = cos(floor(lcg_value()*360)*(pi()/180));
			$shiftZ = sin(floor(lcg_value()*360)*(pi()/180));
			$nbt = new CompoundTag("", [
				"Pos" => new EnumTag("Pos", [
					new DoubleTag("", $x+($shiftX*$randomRange)+0.5),
					new DoubleTag("", $y),
					new DoubleTag("", $z+($shiftZ*$randomRange)+0.5)
				]),
				"Motion" => new EnumTag("Motion", [
					new DoubleTag("", 0),
					new DoubleTag("", 0),
					new DoubleTag("", 0)
				]),
				"Rotation" => new EnumTag("Rotation", [
					new FloatTag("", \lcg_value() * 360),
					new FloatTag("", 0)
				]),
			]);
			$entity = Entity::createEntity(PigZombie::NETWORK_ID, $this->getLevel()->getChunk($x+($shiftX*$randomRange) >> 4, $z+($shiftZ*$randomRange) >> 4), $nbt);
			$entity->spawnToAll();
		}
	}
	
	public function glow($part){
		$x = $this->x;
		$y = $this->y;
		$z = $this->z;
		switch($part){
			case 1:
				$this->level->setBlock(new Vector3($x, $y-1, $z),new GlowingObsidian());
				$this->level->setBlock(new Vector3($x+1, $y-1, $z),new GlowingObsidian());
				$this->level->setBlock(new Vector3($x-1, $y-1, $z),new GlowingObsidian());
				$this->level->setBlock(new Vector3($x, $y-1, $z+1),new GlowingObsidian());
				$this->level->setBlock(new Vector3($x, $y-1, $z-1),new GlowingObsidian());
				break;
			case 2:
				$this->level->setBlock(new Vector3($x+1, $y, $z+1),new GlowingObsidian());
				$this->level->setBlock(new Vector3($x+1, $y, $z-1),new GlowingObsidian());
				$this->level->setBlock(new Vector3($x-1, $y, $z+1),new GlowingObsidian());
				$this->level->setBlock(new Vector3($x-1, $y, $z-1),new GlowingObsidian());
				break;
			case 3:
				$this->level->setBlock(new Vector3($x, $y+1, $z),new GlowingObsidian());
				$this->level->setBlock(new Vector3($x+1, $y+1, $z),new GlowingObsidian());
				$this->level->setBlock(new Vector3($x-1, $y+1, $z),new GlowingObsidian());
				$this->level->setBlock(new Vector3($x, $y+1, $z+1),new GlowingObsidian());
				$this->level->setBlock(new Vector3($x, $y+1, $z-1),new GlowingObsidian());
				break;
			case 4:
				$this->level->setBlock(new Vector3($x+1, $y-1, $z+1),new GlowingObsidian());
				$this->level->setBlock(new Vector3($x+1, $y-1, $z-1),new GlowingObsidian());
				$this->level->setBlock(new Vector3($x-1, $y-1, $z+1),new GlowingObsidian());
				$this->level->setBlock(new Vector3($x-1, $y-1, $z-1),new GlowingObsidian());
				break;
			case 5:
				$this->level->setBlock(new Vector3($x, $y+1, $z),new Obsidian());
				$this->level->setBlock(new Vector3($x+1, $y+1, $z),new Obsidian());
				$this->level->setBlock(new Vector3($x-1, $y+1, $z),new Obsidian());
				$this->level->setBlock(new Vector3($x, $y+1, $z+1),new Obsidian());
				$this->level->setBlock(new Vector3($x, $y+1, $z-1),new Obsidian());
				$this->level->setBlock(new Vector3($x+1, $y+1, $z+1),new Obsidian());
				$this->level->setBlock(new Vector3($x+1, $y+1, $z-1),new Obsidian());
				$this->level->setBlock(new Vector3($x-1, $y+1, $z+1),new Obsidian());
				$this->level->setBlock(new Vector3($x-1, $y+1, $z-1),new Obsidian());
				break;
			case 6:
				$this->level->setBlock(new Vector3($x, $y, $z), new NetherReactor(2));
				$this->level->setBlock(new Vector3($x+1, $y, $z),new Obsidian());
				$this->level->setBlock(new Vector3($x-1, $y, $z),new Obsidian());
				$this->level->setBlock(new Vector3($x, $y, $z+1),new Obsidian());
				$this->level->setBlock(new Vector3($x, $y, $z-1),new Obsidian());
				$this->level->setBlock(new Vector3($x+1, $y, $z+1),new Obsidian());
				$this->level->setBlock(new Vector3($x+1, $y, $z-1),new Obsidian());
				$this->level->setBlock(new Vector3($x-1, $y, $z+1),new Obsidian());
				$this->level->setBlock(new Vector3($x-1, $y, $z-1),new Obsidian());
				break;
			case 7:
				$this->level->setBlock(new Vector3($x, $y-1, $z),new Obsidian());
				$this->level->setBlock(new Vector3($x+1, $y-1, $z),new Obsidian());
				$this->level->setBlock(new Vector3($x-1, $y-1, $z),new Obsidian());
				$this->level->setBlock(new Vector3($x, $y-1, $z+1),new Obsidian());
				$this->level->setBlock(new Vector3($x, $y-1, $z-1),new Obsidian());
				$this->level->setBlock(new Vector3($x+1, $y-1, $z+1),new Obsidian());
				$this->level->setBlock(new Vector3($x+1, $y-1, $z-1),new Obsidian());
				$this->level->setBlock(new Vector3($x-1, $y-1, $z+1),new Obsidian());
				$this->level->setBlock(new Vector3($x-1, $y-1, $z-1),new Obsidian());
				break;
		}
	}

	public function destroy(){
		$this->level->setBlock(new Vector3($this->x, $this->y, $this->z),new NetherReactor(2));
		$this->decay($this->x-8, $this->y-3, $this->z-8, 0, 17, 16, 2, 34, 1, 0, 17, 1);
		$this->decay($this->x-8, $this->y-3, $this->z-8, 1, 16, 1, 2, 34, 1, 0, 17, 16);
		$this->decay($this->x-8, $this->y-3, $this->z-8, 3, 14, 10, 8, 34, 1, 3, 14, 1);
		$this->decay($this->x-8, $this->y-3, $this->z-8, 4, 13, 1, 8, 34, 1, 3, 14, 10);
		$this->decay($this->x-8, $this->y-3, $this->z-8, 5, 12, 6, 14, 34, 1, 5, 12, 1);
		$this->decay($this->x-8, $this->y-3, $this->z-8, 6, 11, 1, 14, 34, 1, 5, 12, 16);	
	}
	
	private function decay($x, $y, $z, $aOne, $aTwo, $aThree, $bOne, $bTwo, $bThree, $cOne, $cTwo, $cThree) {
		for($a = $aOne; $a < $aTwo; $a += $aThree) { //wth those cycles are? TODO simplify if possible(makes server lag)
			for($b = $bOne; $b < $bTwo; $b += $bThree) {
				for($c = $cOne; $c < $cTwo; $c += $cThree) {
					if ($this->level->getBlockIdAt($x+$a, $y+$b, $z+$c) === Block::NETHERRACK && lcg_value() > 0.75){
						$this->level->setBlock(new Vector3($x+$a, $y+$b, $z+$c), new Air());
					}
				}
			}
		}
	}
	
	public function canBeActivated(){
		return \true;
	}
	
	private $possibleLoot = [
		Item::GLOWSTONE_DUST, Item::QUARTZ, Block::CACTUS, Item::SUGARCANE, Block::BROWN_MUSHROOM, Block::RED_MUSHROOM, Item::PUMPKIN_SEEDS, Item::MELON_SEEDS
	];
	
	private $rarePossibleLoot = [
		Item::BOW, Item::BED, Item::BOWL, Item::ARROW, Item::WOODEN_DOOR, Item::FEATHER, Item::PAINTING, Item::BONE, Item::DANDELION
	];
	
	private $core = [
		-1 => ["GCG", "CCC", "GCG"],
		0 => ["C C", " R ", "C C",],
		1 =>[" C ", "CCC", " C "]
	];
}
