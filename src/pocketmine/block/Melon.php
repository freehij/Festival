<?php


namespace pocketmine\block;

use pocketmine\item\Item;

class Melon extends Transparent{

	protected $id = self::MELON_BLOCK;

	public function __construct(){

	}

	public function getName(){
		return "Melon Block";
	}

	public function getHardness(){
		return 5;
	}

	public function getDrops(Item $item){
		return [
			[Item::MELON_SLICE, 0, \mt_rand(3, 7)],
		];
	}
}