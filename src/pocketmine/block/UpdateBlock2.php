<?php

namespace pocketmine\block;

use pocketmine\item\Item;

class UpdateBlock2 extends Solid{

	protected $id = self::UPDATE_BLOCK2;

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
