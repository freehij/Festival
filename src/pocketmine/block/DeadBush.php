<?php


namespace pocketmine\block;

use pocketmine\level\Level;
use pocketmine\item\Item;
use pocketmine\Player;

class DeadBush extends Flowable{

	protected $id = self::DEAD_BUSH;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		return "Dead Bush";
	}
	
	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$down = $this->getSide(0);
		if($down->getId() === Block::SAND){
			$this->getLevel()->setBlock($block, $this, true);
			return true;
		}

		return false;
	}

	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			if($this->getSide(0)->isTransparent()){
				$this->getLevel()->useBreakOn($this);

				return Level::BLOCK_UPDATE_NORMAL;
			}
		}

		return false;
	}

}
