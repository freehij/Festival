<?php


namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\Player;

class SnowLayer extends Flowable{

	protected $id = self::SNOW_LAYER;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		return "Snow Layer";
	}

	public function canBeReplaced(){
		return \true;
	}

	public function getHardness(){
		return 0.5;
	}


	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = \null){
		$down = $this->getSide(0);
		if($down->isSolid()){
			$this->getLevel()->setBlock($block, $this, \true);

			return \true;
		}

		return \false;
	}

	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			if($this->getSide(0)->getId() === self::AIR){ //Replace with common break method
				$this->getLevel()->setBlock($this, new Air(), \true);

				return Level::BLOCK_UPDATE_NORMAL;
			}
		}

		return \false;
	}

	public function getDrops(Item $item){
		if($item->isShovel() !== \false){
			return [
				[Item::SNOWBALL, 0, 1],
			];
		}

		return [];
	}
}