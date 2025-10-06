<?php


namespace pocketmine\block;

use pocketmine\item\Item;

class WoodDoor extends Door{

	protected $id = self::WOOD_DOOR_BLOCK;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		return "Wood Door Block";
	}

	public function canBeActivated(){
		return \true;
	}

	public function getHardness(){
		return 15;
	}

	public function getDrops(Item $item){
		return [
			[Item::WOODEN_DOOR, 0, 1],
		];
	}
}