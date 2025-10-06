<?php


namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\math\AxisAlignedBB;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Bed extends Transparent{

	protected $id = self::BED_BLOCK;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function canBeActivated(){
		return \true;
	}

	public function getHardness(){
		return 1;
	}

	public function getName(){
		return "Bed Block";
	}

	protected function recalculateBoundingBox(){
		return new AxisAlignedBB(
			$this->x,
			$this->y,
			$this->z,
			$this->x + 1,
			$this->y + 0.5625,
			$this->z + 1
		);
	}

	public function onActivate(Item $item, Player $player = \null){

		$time = $this->getLevel()->getTime() % Level::TIME_FULL;

		$isNight = ($time >= Level::TIME_NIGHT and $time < Level::TIME_SUNRISE);

		if($player instanceof Player and !$isNight){
			$player->sendMessage(TextFormat::GRAY . "You can only sleep at night");
			return \true;
		}

		$blockNorth = $this->getSide(2); //Gets the blocks around them
		$blockSouth = $this->getSide(3);
		$blockEast = $this->getSide(5);
		$blockWest = $this->getSide(4);
		if(($this->meta & 0x08) === 0x08){ //This is the Top part of bed
			$b = $this;
		}else{ //Bottom Part of Bed
			if($blockNorth->getId() === $this->id and ($blockNorth->meta & 0x08) === 0x08){
				$b = $blockNorth;
			}elseif($blockSouth->getId() === $this->id and ($blockSouth->meta & 0x08) === 0x08){
				$b = $blockSouth;
			}elseif($blockEast->getId() === $this->id and ($blockEast->meta & 0x08) === 0x08){
				$b = $blockEast;
			}elseif($blockWest->getId() === $this->id and ($blockWest->meta & 0x08) === 0x08){
				$b = $blockWest;
			}else{
				if($player instanceof Player){
					$player->sendMessage(TextFormat::GRAY . "This bed is incomplete");
				}

				return \true;
			}
		}

		if($player instanceof Player and $player->sleepOn($b) === \false){
			$player->sendMessage(TextFormat::GRAY . "This bed is occupied");
		}

		return \true;
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = \null){
		$down = $this->getSide(0);
		if($down->isTransparent() === \false){
			$faces = [
				0 => 3,
				1 => 4, //affected
				2 => 2,
				3 => 5, //affected
			];
			$d = $player instanceof Player ? $player->getDirection() : 0;
			$next = $this->getSide($faces[(($d + 3) % 4)]);
			$downNext = $next->getSide(0);
			if($next->canBeReplaced() === \true and $downNext->isTransparent() === \false){
				$meta = (($d + 3) % 4) & 0x03;
				$this->getLevel()->setBlock($block, Block::get($this->id, $meta), \true, \true);
				$this->getLevel()->setBlock($next, Block::get($this->id, $meta | 0x08), \true, \true);

				return \true;
			}
		}

		return \false;
	}

	public function onBreak(Item $item){
		$blockNorth = $this->getSide(2); //Gets the blocks around them
		$blockSouth = $this->getSide(3);
		$blockEast = $this->getSide(5);
		$blockWest = $this->getSide(4);

		if(($this->meta & 0x08) === 0x08){ //This is the Top part of bed
			switch($this->meta & 0x7){
				case 0:
					if($blockNorth->getId() === $this->id) $this->getLevel()->setBlock($blockNorth, new Air(), \true, \true);
					break;
				case 1:
					if($blockEast->getId() === $this->id) $this->getLevel()->setBlock($blockEast, new Air(), \true, \true);
					break;
				case 2:
					if($blockSouth->getId() === $this->id) $this->getLevel()->setBlock($blockSouth, new Air(), \true, \true);
					break;
				case 3:
					if($blockWest->getId() === $this->id) $this->getLevel()->setBlock($blockWest, new Air(), \true, \true);
					break;
			}
		}else{ //Bottom Part of Bed
			switch($this->meta & 0x7){
				case 0:
					if($blockSouth->getId() === $this->id) $this->getLevel()->setBlock($blockSouth, new Air(), \true, \true);
					break;
				case 1:
					if($blockWest->getId() === $this->id) $this->getLevel()->setBlock($blockWest, new Air(), \true, \true);
					break;
				case 2:
					if($blockNorth->getId() === $this->id) $this->getLevel()->setBlock($blockNorth, new Air(), \true, \true);
					break;
				case 3:
					if($blockEast->getId() === $this->id) $this->getLevel()->setBlock($blockEast, new Air(), \true, \true);
					break;
			}
		}
		$this->getLevel()->setBlock($this, new Air(), \true, \true);

		return \true;
	}

	public function getDrops(Item $item){
		return [
			[Item::BED, 0, 1],
		];
	}

}