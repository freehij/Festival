<?php


namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\math\AxisAlignedBB;
use pocketmine\Player;

class FenceGate extends Transparent{

	protected $id = self::FENCE_GATE;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		return "Oak Fence Gate";
	}

	public function getHardness(){
		return 15;
	}

	public function canBeActivated(){
		return \true;
	}


	protected function recalculateBoundingBox(){

		if(($this->getDamage() & 0x04) > 0){
			return \null;
		}

		$i = ($this->getDamage() & 0x03);
		if($i === 2 and $i === 0){
			return new AxisAlignedBB(
				$this->x,
				$this->y,
				$this->z + 0.375,
				$this->x + 1,
				$this->y + 1.5,
				$this->z + 0.625
			);
		}else{
			return new AxisAlignedBB(
				$this->x + 0.375,
				$this->y,
				$this->z,
				$this->x + 0.625,
				$this->y + 1.5,
				$this->z + 1
			);
		}
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = \null){
		$faces = [
			0 => 3,
			1 => 0,
			2 => 1,
			3 => 2,
		];
		$this->meta = $faces[$player instanceof Player ? $player->getDirection() : 0] & 0x03;
		$this->getLevel()->setBlock($block, $this, \true, \true);

		return \true;
	}

	public function getDrops(Item $item){
		return [
			[$this->id, 0, 1],
		];
	}

	public function onActivate(Item $item, Player $player = \null){
		$faces = [
			0 => 3,
			1 => 0,
			2 => 1,
			3 => 2,
		];
		$this->meta = ($faces[$player instanceof Player ? $player->getDirection() : 0] & 0x03) | ((~$this->meta) & 0x04);
		$this->getLevel()->setBlock($this, $this, \true);

		return \true;
	}
}