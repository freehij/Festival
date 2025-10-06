<?php


namespace pocketmine\block;


class SandstoneStairs extends Stair{

	protected $id = self::SANDSTONE_STAIRS;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		return "Sandstone Stairs";
	}

}