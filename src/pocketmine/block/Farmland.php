<?php


namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\math\AxisAlignedBB;
use pocketmine\entity\Entity;

class Farmland extends Solid{

	protected $id = self::FARMLAND;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		return "Farmland";
	}

	public function getHardness(){
		return 3;
	}
	
	public function onFall(Entity $entity, $fallDistance){
		$rv = lcg_value();
		if($rv < ($fallDistance - 0.5)){
			$this->level->setBlock($this, new Dirt(), false, true);
		}
	}
	
	protected function recalculateBoundingBox(){
		return new AxisAlignedBB(
			$this->x,
			$this->y,
			$this->z,
			$this->x + 1,
			$this->y + 0.9375,
			$this->z + 1
		);
	}

	public function getDrops(Item $item){
		return [
			[Item::DIRT, 0, 1],
		];
	}
}