<?php


namespace pocketmine\block;

use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\EnumTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\Player;

abstract class Fallable extends Solid{

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = \null){
		$ret = $this->getLevel()->setBlock($this, $this, \true, \true);

		return $ret;
	}

	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			$down = $this->getSide(0);
			if($down->getId() === self::AIR or ($down instanceof Liquid)){
				$ret = $this->getLevel()->setBlock($this, new Air(), \true, \true);
				$fall = Entity::createEntity("FallingSand", $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4), new CompoundTag("", [
					"Pos" => new EnumTag("Pos", [
						new DoubleTag("", $this->x + 0.5),
						new DoubleTag("", $this->y),
						new DoubleTag("", $this->z + 0.5)
					]),
					"Motion" => new EnumTag("Motion", [
						new DoubleTag("", 0),
						new DoubleTag("", 0),
						new DoubleTag("", 0)
					]),
					"Rotation" => new EnumTag("Rotation", [
						new FloatTag("", 0),
						new FloatTag("", 0)
					]),
					"TileID" => new IntTag("TileID", $this->getId()),
					"Data" => new ByteTag("Data", $this->getDamage()),
				]));

				$fall->spawnToAll();
			}
		}
	}
}
