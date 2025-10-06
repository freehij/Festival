<?php


namespace pocketmine\block;


class QuartzStairs extends Stair{

	protected $id = self::QUARTZ_STAIRS;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		return "Quartz Stairs";
	}

}