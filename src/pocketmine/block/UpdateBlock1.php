<?php

namespace pocketmine\block;

use pocketmine\item\Item;

class UpdateBlock1 extends Solid{

	protected $id = self::UPDATE_BLOCK1;

	public function __construct(){

	}

	public function getBreakTime(Item $item){
		return 0;
	}

	public function getName(){
		return "Update Block";
	}

	public function getHardness(){
		return 2.5;
	}
}
