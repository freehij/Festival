<?php



namespace pocketmine\block;

use pocketmine\item\Item;

class MonsterSpawner extends Solid{

	protected $id = self::MONSTER_SPAWNER;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getHardness(){
		return 25;
	}

	public function getName(){
		return "Monster Spawner";
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
		return [];
	}
}