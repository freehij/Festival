<?php


namespace pocketmine\block;


class Bookshelf extends Solid{

	protected $id = self::BOOKSHELF;

	public function __construct(){

	}

	public function getName(){
		return "Bookshelf";
	}

	public function getHardness(){
		return 7.5;
	}

}