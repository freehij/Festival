<?php


namespace pocketmine\block;

use pocketmine\item\Item;

class Gravel extends Fallable{

	protected $id = self::GRAVEL;

	public function __construct(){

	}

	public function getName(){
		return "Gravel";
	}

	public function getHardness(){
		return 3;
	}

	public function getDrops(Item $item){
		if(\mt_rand(1, 10) === 1){
			return [
				[Item::FLINT, 0, 1],
			];
		}

		return [
			[Item::GRAVEL, 0, 1],
		];
	}

}