<?php


namespace pocketmine\block;

use pocketmine\item\Item;

class StoneBricks extends Solid{

	protected $id = self::STONE_BRICKS;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getHardness(){
		return 30;
	}

	public function getName(){
		static $names = [
			0 => "Stone Bricks",
			1 => "Mossy Stone Bricks",
			2 => "Cracked Stone Bricks",
			3 => "Chiseled Stone Bricks",
		];
		return $names[$this->meta & 0x03];
	}

	public function getBreakTime(Item $item){

		switch($item->isPickaxe()){
			case 5:
				return 0.4;
			case 4:
				return 0.5;
			case 3:
				return 0.75;
			case 2:
				return 0.25;
			case 1:
				return 1.5;
			default:
				return 7.5;
		}
	}

	public function getDrops(Item $item){
		if($item->isPickaxe() >= 1){
			return [
				[Item::STONE_BRICKS, $this->meta & 0x03, 1],
			];
		}else{
			return [];
		}
	}

}