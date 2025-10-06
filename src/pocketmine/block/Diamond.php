<?php


namespace pocketmine\block;

use pocketmine\item\Item;

class Diamond extends Solid{

	protected $id = self::DIAMOND_BLOCK;

	public function __construct(){

	}

	public function getHardness(){
		return 30;
	}

	public function getName(){
		return "Diamond Block";
	}

	public function getBreakTime(Item $item){
		switch($item->isPickaxe()){
			case 5:
				return 0.95;
			case 4:
				return 1.25;
			default:
				return 25;
		}
	}

	public function getDrops(Item $item){
		if($item->isPickaxe() >= 4){
			return [
				[Item::DIAMOND_BLOCK, 0, 1],
			];
		}else{
			return [];
		}
	}
}