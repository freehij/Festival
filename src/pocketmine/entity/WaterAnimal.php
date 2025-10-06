<?php


namespace pocketmine\entity;

abstract class WaterAnimal extends Creature implements Ageable{
	protected function initEntity(){
		parent::initEntity();
		if($this->getDataProperty(self::DATA_AGEABLE) === \null){
			$this->setDataProperty(self::DATA_AGEABLE, self::DATA_TYPE_BYTE, 0);
		}
	}

	public function isBaby(){
		return $this->getDataFlag(self::DATA_AGEABLE, self::DATA_FLAG_BABY);
	}
}
