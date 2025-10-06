<?php


namespace pocketmine\block;

use pocketmine\item\Item;

class DiamondOre extends Solid{

	protected $id = self::DIAMOND_ORE;

	public function __construct(){

	}

	public function getHardness(){
		return 15;
	}

	public function getName(){
		return "Diamond Ore";
	}

	public function getBreakTime(Item $item){
		switch($item->isPickaxe()){
			case 5:
				return 0.6;
			case 4:
				return 0.75;
			default:
				return 15;
		}
	}

	public function getDrops(Item $item){
		if($item->isPickaxe() >= 4){
			return [
				[Item::DIAMOND, 0, 1],
			];
		}else{
			return [];
		}
	}
}