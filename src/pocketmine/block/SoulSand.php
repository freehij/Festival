<?php


namespace pocketmine\block;


use pocketmine\math\AxisAlignedBB;

class SoulSand extends Solid{

	protected $id = self::SOUL_SAND;

	public function __construct(){

	}

	public function getName(){
		return "Soul Sand";
	}

	public function getHardness(){
		return 2.5;
	}

	protected function recalculateBoundingBox(){

		return new AxisAlignedBB(
			$this->x,
			$this->y,
			$this->z,
			$this->x + 1,
			$this->y + 1 - 0.125,
			$this->z + 1
		);
	}

}