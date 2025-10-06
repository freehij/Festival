<?php


namespace pocketmine\block;

use pocketmine\item\Item;

class Sandstone extends Solid{

	protected $id = self::SANDSTONE;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getHardness(){
		return 4;
	}

	public function getName(){
		static $names = [
			0 => "Sandstone",
			1 => "Chiseled Sandstone",
			2 => "Smooth Sandstone",
			3 => "",
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
				[Item::SANDSTONE, $this->meta & 0x03, 1],
			];
		}else{
			return [];
		}
	}

}