<?php


namespace pocketmine\block;

use pocketmine\item\Item;

class IronOre extends Solid{

	protected $id = self::IRON_ORE;

	public function __construct(){

	}

	public function getName(){
		return "Iron Ore";
	}

	public function getHardness(){
		return 15;
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
				[Item::IRON_ORE, 0, 1],
			];
		}else{
			return [];
		}
	}
}