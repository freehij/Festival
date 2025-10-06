<?php


namespace pocketmine\block;


class NetherBrickStairs extends Stair{

	protected $id = self::NETHER_BRICKS_STAIRS;

	public function getName(){
		return "Nether Bricks Stairs";
	}

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

}