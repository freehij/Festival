<?php


namespace pocketmine\block;

use pocketmine\entity\Entity;
use pocketmine\item\Item;

class Cobweb extends Flowable{

	protected $id = self::COBWEB;

	public function __construct(){

	}

	public function hasEntityCollision(){
		return \true;
	}

	public function getName(){
		return "Cobweb";
	}

	public function getHardness(){
		return 25;
	}

	public function onEntityCollide(Entity $entity){
		$entity->resetFallDistance();
	}

	public function getDrops(Item $item){
		return [[$item->getId() === Item::SHEARS ? self::COBWEB : Item::STRING, 0, 1]];
	}
}