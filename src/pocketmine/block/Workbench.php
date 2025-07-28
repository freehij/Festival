<?php


namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\Player;

//TODO: check orientation
class Workbench extends Solid{

	protected $id = self::WORKBENCH;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function canBeActivated(){
		return \true;
	}

	public function getHardness(){
		return 15;
	}

	public function getName(){
		return "Crafting Table";
	}

	public function onActivate(Item $item, Player $player = \null){
		if($player instanceof Player){
			$player->craftingType = 1;
		}

		return \true;
	}

	public function getDrops(Item $item){
		return [
			[$this->id, 0, 1],
		];
	}
}