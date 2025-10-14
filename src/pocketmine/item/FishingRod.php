<?php

namespace pocketmine\item;

use pocketmine\item\Item;

class FishingRod extends Item {
	public function __construct($meta = 0, $count = 1){
		parent::__construct(Item::FISHING_ROD, $meta, $count, "Fishing Rod");
	}

	public function getMaxStackSize() : int {
		return 1;
	}
}
