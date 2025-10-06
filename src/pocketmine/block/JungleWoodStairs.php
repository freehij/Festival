<?php


namespace pocketmine\block;

use pocketmine\item\Item;

class JungleWoodStairs extends Stair{

	protected $id = self::JUNGLE_WOOD_STAIRS;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		return "Jungle Wood Stairs";
	}

	public function getDrops(Item $item){
		return [
			[$this->id, 0, 1],
		];
	}
}