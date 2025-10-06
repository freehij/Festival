<?php


namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\Player;

//TODO: check orientation
class Stonecutter extends Solid{

	protected $id = self::STONECUTTER;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		return "Stonecutter";
	}

	public function canBeActivated(){
		return \true;
	}

	public function onActivate(Item $item, Player $player = \null){
		if($player instanceof Player){
			$player->craftingType = 2;
		}

		return \true;
	}

	public function getDrops(Item $item){
		if($item->isPickaxe() >= 1){
			return [
				[Item::STONECUTTER, 0, 1],
			];
		}else{
			return [];
		}
	}
}