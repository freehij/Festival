<?php


namespace pocketmine\block;


class StoneBrickStairs extends Stair{

	protected $id = self::STONE_BRICK_STAIRS;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		return "Stone Brick Stairs";
	}

}