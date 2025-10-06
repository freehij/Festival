<?php


namespace pocketmine\block;

use pocketmine\item\Item;

class EndStone extends Solid{

	protected $id = self::END_STONE;

	public function __construct(){

	}

	public function getName(){
		return "End Stone";
	}

	public function getHardness(){
		return 45;
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
}