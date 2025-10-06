<?php


namespace pocketmine\block;


class WallSign extends SignPost{
	static $meta2side = [
		2 => 3,
		3 => 2,
		4 => 5,
		5 => 4
	];
	protected $id = self::WALL_SIGN;

	public function getName(){
		return "Wall Sign";
	}

	public function onUpdate($type){
		$attached = $this->getSide(self::$meta2side[$this->meta]);
		if(!$attached->isSolid() && ($attached->getId() !== Block::WALL_SIGN && $attached->getId() !== Block::SIGN_POST)){
			$this->getLevel()->setBlock($this, new Air(), \true, \true);
		}
		
		return $type;
	}
}