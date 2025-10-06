<?php


namespace pocketmine\block;


class CobblestoneStairs extends Stair{

	protected $id = self::COBBLESTONE_STAIRS;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		return "Cobblestone Stairs";
	}

}