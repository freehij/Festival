<?php


namespace pocketmine\block;

use pocketmine\item\Item;

class Obsidian extends Solid{

	protected $id = self::OBSIDIAN;

	public function __construct(){

	}

	public function getName(){
		return "Obsidian";
	}

	public function getHardness(){
		return 6000;
	}

	public function getBreakTime(Item $item){

		if($item->isPickaxe() >= 5){
			return 9.4;
		}else{
			return 250;
		}
	}

	public function getDrops(Item $item){
		if($item->isPickaxe() >= 5){
			return [
				[Item::OBSIDIAN, 0, 1],
			];
		}else{
			return [];
		}
	}
}