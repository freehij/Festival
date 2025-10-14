<?php

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\Player;

class DoubleTallGrass extends Flowable
{
	public static $NAMES = [
		0 => "Sunflower",
		1 => "Lilac",
		2 => "Tall Grass",
		3 => "Tall Fern",
		4 => "Rose Bush",
		5 => "Peony",
	];
	public function __construct($meta = 0){
		$this->mwta = $meta;
		//parent::__construct(DOUBLE_PLANT, $meta, "Tall Grass");
		//$this->name = self::$NAMES[$this->meta] ?? "Tall Grass";
	}
	public function getHardness(){
		return 0;
	}

	public function getName(){
		return self::$NAMES[$this->getMetadata() % 6];
	}

	public function getDrops(Item $item){ //TODO vanilla?
		return [];
	}
	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = \null){
		$down = $this->getSide(0);
		if($down->getId() == 2 or $down->getId() == 3){ //TODO destroy the block above(tested only in creative)
			$this->getLevel()->setBlock($block, $this, true, true);
			$this->getLevel()->setBlock($block->add(0, 1, 0), new DoubleTallGrass($this->meta ^ 0x8), true, true);
			return true;
		}
		return false;
	}

}

