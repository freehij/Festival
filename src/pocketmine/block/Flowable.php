<?php


namespace pocketmine\block;


use pocketmine\item\Item;

abstract class Flowable extends Transparent{

	public function canBeFlowedInto(){
		return \true;
	}

	public function getBreakTime(Item $item){
		return 0;
	}

	public function isSolid(){
		return \false;
	}

	public function getBoundingBox(){
		return \null;
	}

	public function getHardness(){
		return 0;
	}
}