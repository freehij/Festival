<?php


namespace pocketmine\block;


class Furnace extends BurningFurnace{

	protected $id = self::FURNACE;

	public function getName(){
		return "Furnace";
	}
}