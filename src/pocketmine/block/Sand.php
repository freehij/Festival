<?php


namespace pocketmine\block;


class Sand extends Fallable{

	protected $id = self::SAND;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getHardness(){
		return 2.5;
	}

	public function getName(){
		if($this->meta === 0x01){
			return "Red Sand";
		}

		return "Sand";
	}

}