<?php


namespace pocketmine\block;

use pocketmine\item\Item;

class HardenedClay extends Solid{

	protected $id = self::HARDENED_CLAY;

	public function __construct(){

	}

	public function getName(){
		return "Hardened Clay";
	}

	public function getHardness(){
		return 30;
	}

	public function getBreakTime(Item $item){
		switch($item->isPickaxe()){
			case 5:
				return 0.25;
			case 4:
				return 0.35;
			case 3:
				return 0.5;
			case 2:
				return 0.2;
			case 1:
				return 0.95;
			default:
				return 6.25;
		}
	}

}