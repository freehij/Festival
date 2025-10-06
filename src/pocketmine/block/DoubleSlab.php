<?php


namespace pocketmine\block;

use pocketmine\item\Item;

class DoubleSlab extends Solid{

	protected $id = self::DOUBLE_SLAB;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getHardness(){
		return 30;
	}

	public function getName(){
		static $names = [
			0 => "Stone",
			1 => "Sandstone",
			2 => "Wooden",
			3 => "Cobblestone",
			4 => "Brick",
			5 => "Stone Brick",
			6 => "Quartz",
			7 => "Stone",
		];
		return "Double " . $names[$this->meta & 0x07] . " Slab";
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
				return 10;
		}
	}

	public function getDrops(Item $item){
		if($item->isPickaxe() >= 1){
			return [
				[Item::SLAB, $this->meta & 0x07, 2],
			];
		}else{
			return [];
		}
	}

}