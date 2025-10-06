<?php


namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\level\format\FullChunk;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\EnumTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\Player;

class SpawnEgg extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::SPAWN_EGG, $meta, $count, "Spawn Egg");
	}

	public function canBeActivated(){
		return \true;
	}

	public function onActivate(Level $level, Player $player, Block $block, Block $target, $face, $fx, $fy, $fz){
		$entity = \null;
		$chunk = $level->getChunk($block->getX() >> 4, $block->getZ() >> 4);

		if(!($chunk instanceof FullChunk)){
			return \false;
		}

		$nbt = new CompoundTag("", [
			"Pos" => new EnumTag("Pos", [
				new DoubleTag("", $block->getX() + 0.5),
				new DoubleTag("", $block->getY()),
				new DoubleTag("", $block->getZ() + 0.5)
			]),
			"Motion" => new EnumTag("Motion", [
				new DoubleTag("", 0),
				new DoubleTag("", 0),
				new DoubleTag("", 0)
			]),
			"Rotation" => new EnumTag("Rotation", [
				new FloatTag("", \lcg_value() * 360),
				new FloatTag("", 0)
			]),
		]);
		
		$entity = Entity::createEntity($this->meta, $chunk, $nbt);

		if($entity instanceof Entity){
			if($player->isSurvival()){
				--$this->count;
			}
			$entity->spawnToAll();

			return \true;
		}

		return \false;
	}
}