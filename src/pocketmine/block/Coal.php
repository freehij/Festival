<?php


namespace pocketmine\block;

use pocketmine\item\Item;

class Coal extends Solid{

	protected $id = self::COAL_BLOCK;

	public function __construct(){

	}

	public function getHardness(){
		return 30;
	}

	public function getName(){
		return "Coal Block";
	}

	public function getBreakTime(Item $item){
		switch($item->isPickaxe()){
			case 5:
				return 0.95;
			case 4:
				return 1.25;
			case 3:
				return 1.9;
			case 2:
				return 0.65;
			case 1:
				return 3.75;
			default:
				return 25;
		}
	}

	public function getDrops(Item $item){
		if($item->isPickaxe() >= 1){
			return [
				[Item::COAL_BLOCK, 0, 1],
			];
		}else{
			return [];
		}
	}
}