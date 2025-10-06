<?php


namespace pocketmine\block;

use pocketmine\item\Item;

class LapisOre extends Solid{

	protected $id = self::LAPIS_ORE;

	public function __construct(){

	}

	public function getHardness(){
		return 15;
	}

	public function getName(){
		return "Lapis Ore";
	}

	public function getBreakTime(Item $item){

		switch($item->isPickaxe()){
			case 5:
				return 0.6;
			case 4:
				return 0.75;
			case 3:
				return 1.15;
			default:
				return 15;
		}
	}

	public function getDrops(Item $item){
		if($item->isPickaxe() >= 3){
			return [
				[Item::DYE, 4, \mt_rand(4, 8)],
			];
		}else{
			return [];
		}
	}

}