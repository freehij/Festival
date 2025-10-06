<?php


namespace pocketmine\block;

use pocketmine\item\Item;

class CoalOre extends Solid{

	protected $id = self::COAL_ORE;

	public function __construct(){

	}

	public function getHardness(){
		return 15;
	}

	public function getName(){
		return "Coal Ore";
	}

	public function getBreakTime(Item $item){
		switch($item->isPickaxe()){
			case 5:
				return 0.6;
			case 4:
				return 0.75;
			case 3:
				return 1.15;
			case 2:
				return 0.4;
			case 1:
				return 2.25;
			default:
				return 15;
		}
	}

	public function getDrops(Item $item){
		if($item->isPickaxe() >= 1){
			return [
				[Item::COAL, 0, 1],
			];
		}else{
			return [];
		}
	}

}