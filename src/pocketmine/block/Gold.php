<?php


namespace pocketmine\block;

use pocketmine\item\Item;

class Gold extends Solid{

	protected $id = self::GOLD_BLOCK;

	public function __construct(){

	}

	public function getName(){
		return "Gold Block";
	}

	public function getHardness(){
		return 30;
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
				[Item::GOLD_BLOCK, 0, 1],
			];
		}else{
			return [];
		}
	}
}