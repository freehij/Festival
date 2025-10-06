<?php

namespace pocketmine\block;

use pocketmine\item\Item;

class reserved6 extends Solid{

	protected $id = self::RESERVED6;

	public function __construct(){

	}

	public function getBreakTime(Item $item){
		return 0;
	}

	public function getName(){
		return "reserved6";
	}
}
