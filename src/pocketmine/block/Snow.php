<?php


namespace pocketmine\block;


class Snow extends Solid{

	protected $id = self::SNOW_BLOCK;

	public function __construct(){

	}

	public function getHardness(){
		return 1;
	}

	public function getName(){
		return "Snow Block";
	}

}