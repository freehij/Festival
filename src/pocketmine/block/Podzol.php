<?php


namespace pocketmine\block;

class Podzol extends Solid{

	protected $id = self::PODZOL;

	public function __construct(){

	}

	public function getName(){
		return "Podzol";
	}

	public function getHardness(){
		return 2.5;
	}
}