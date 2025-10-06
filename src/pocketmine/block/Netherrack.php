<?php


namespace pocketmine\block;

use pocketmine\item\Item;

class Netherrack extends Solid{

	protected $id = self::NETHERRACK;

	public function __construct(){

	}

	public function getName(){
		return "Netherrack";
	}

	public function getHardness(){
		return 2;
	}

	public function getBreakTime(Item $item){

		switch($item->isPickaxe()){
			case 5:
				return 0.1;
			case 4:
				return 0.1;
			case 3:
				return 0.15;
			case 2:
				return 0.05;
			case 1:
				return 0.3;
			default:
				return 2;
		}
	}

	public function getDrops(Item $item){
		if($item->isPickaxe() >= 1){
			return [
				[Item::NETHERRACK, 0, 1],
			];
		}else{
			return [];
		}
	}
}