<?php


namespace pocketmine\item;


class CookedFish extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::RAW_FISH, $meta, $count, "Cooked Fish");
		if($this->meta === 1){
			$this->name = "Cooked Salmon";
		}
	}

}