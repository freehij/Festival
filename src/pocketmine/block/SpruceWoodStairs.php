<?php


namespace pocketmine\block;

use pocketmine\item\Item;

class SpruceWoodStairs extends Stair{

	protected $id = self::SPRUCE_WOOD_STAIRS;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		return "Spruce Wood Stairs";
	}

	public function getDrops(Item $item){
		return [
			[$this->id, 0, 1],
		];
	}
}