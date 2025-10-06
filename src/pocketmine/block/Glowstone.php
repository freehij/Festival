<?php


namespace pocketmine\block;

use pocketmine\item\Item;

class Glowstone extends Transparent{

	protected $id = self::GLOWSTONE_BLOCK;

	public function __construct(){

	}

	public function getName(){
		return "Glowstone";
	}

	public function getHardness(){
		return 1.5;
	}

	public function getLightLevel(){
		return 15;
	}

	public function getDrops(Item $item){
		return [
			[Item::GLOWSTONE_DUST, 0, \mt_rand(2, 4)],
		];
	}
}