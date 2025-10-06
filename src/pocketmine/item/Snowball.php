<?php


namespace pocketmine\item;


class Snowball extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::SNOWBALL, $meta, $count, "Snowball");
	}

	public function getMaxStackSize(){
		return 16;
	}

}