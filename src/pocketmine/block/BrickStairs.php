<?php


namespace pocketmine\block;


class BrickStairs extends Stair{

	protected $id = self::BRICK_STAIRS;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		return "Brick Stairs";
	}

}