<?php


namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\math\AxisAlignedBB;

class EndPortalFrame extends Solid{

	protected $id = self::END_PORTAL_FRAME;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getLightLevel(){
		return 1;
	}

	public function getName(){
		return "End Portal Frame";
	}

	public function getHardness(){
		return 18000000;
	}

	public function isBreakable(Item $item){
		return \false;
	}

	protected function recalculateBoundingBox(){

		return new AxisAlignedBB(
			$this->x,
			$this->y,
			$this->z,
			$this->x + 1,
			$this->y + (($this->getDamage() & 0x04) > 0 ? 1 : 0.8125),
			$this->z + 1
		);
	}
}