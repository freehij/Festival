<?php


namespace pocketmine\block;

use pocketmine\item\Item;

class Quartz extends Solid{

	protected $id = self::QUARTZ_BLOCK;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		static $names = [
			0 => "Quartz Block",
			1 => "Chiseled Quartz Block",
			2 => "Quartz Pillar",
			3 => "Quartz Pillar",
		];
		return $names[$this->meta & 0x03];
	}

	public function getBreakTime(Item $item){

		switch($item->isPickaxe()){
			case 5:
				return 0.15;
			case 4:
				return 0.2;
			case 3:
				return 0.3;
			case 2:
				return 0.1;
			case 1:
				return 0.6;
			default:
				return 4;
		}
	}

	public function getDrops(Item $item){
		if($item->isPickaxe() >= 1){
			return [
				[Item::QUARTZ_BLOCK, $this->meta & 0x03, 1],
			];
		}else{
			return [];
		}
	}
}