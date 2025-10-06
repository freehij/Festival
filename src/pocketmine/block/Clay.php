<?php


namespace pocketmine\block;

use pocketmine\item\Item;

class Clay extends Solid{

	protected $id = self::CLAY_BLOCK;

	public function __construct(){

	}

	public function getHardness(){
		return 3;
	}

	public function getName(){
		return "Clay Block";
	}

	public function getDrops(Item $item){
		return [
			[Item::CLAY, 0, 4],
		];
	}
}