<?php


namespace pocketmine\block;

use pocketmine\item\Item;

class DarkOakWoodStairs extends Stair{

	protected $id = self::DARK_OAK_WOOD_STAIRS;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		return "Dark Oak Wood Stairs";
	}

	public function getDrops(Item $item){
		return [
			[$this->id, 0, 1],
		];
	}
}