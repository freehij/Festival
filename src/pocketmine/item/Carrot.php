<?php


namespace pocketmine\item;

use pocketmine\block\Block;

class Carrot extends Item{
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::CARROT_BLOCK);
		parent::__construct(self::CARROT, 0, $count, "Carrot");
	}
}