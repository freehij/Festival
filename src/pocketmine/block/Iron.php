<?php


namespace pocketmine\block;

use pocketmine\item\Item;

class Iron extends Solid{

	protected $id = self::IRON_BLOCK;

	public function __construct(){

	}

	public function getName(){
		return "Iron Block";
	}

	public function getHardness(){
		return 30;
	}

	public function getBreakTime(Item $item){
		switch($item->isPickaxe()){
			case 5:
				return 0.95;
			case 4:
				return 1.25;
			case 3:
				return 1.9;
			default:
				return 25;
		}
	}

	public function getDrops(Item $item){
		if($item->isPickaxe() >= 3){
			return [
				[Item::IRON_BLOCK, 0, 1],
			];
		}else{
			return [];
		}
	}
}