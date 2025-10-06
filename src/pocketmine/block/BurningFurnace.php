<?php


namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\EnumTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\tile\Furnace;
use pocketmine\tile\Tile;

class BurningFurnace extends Solid{

	protected $id = self::BURNING_FURNACE;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName(){
		return "Burning Furnace";
	}

	public function canBeActivated(){
		return \true;
	}

	public function getHardness(){
		return 17.5;
	}

	public function getLightLevel(){
		return 13;
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = \null){
		$faces = [
			0 => 4,
			1 => 2,
			2 => 5,
			3 => 3,
		];
		$this->meta = $faces[$player instanceof Player ? $player->getDirection() : 0];
		$this->getLevel()->setBlock($block, $this, \true, \true);
		$nbt = new CompoundTag("", [
			new EnumTag("Items", []),
			new StringTag("id", Tile::FURNACE),
			new IntTag("x", $this->x),
			new IntTag("y", $this->y),
			new IntTag("z", $this->z)
		]);
		$nbt->Items->setTagType(NBT::TAG_Compound);
		Tile::createTile("Furnace", $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4), $nbt);

		return \true;
	}

	public function onBreak(Item $item){
		$this->getLevel()->setBlock($this, new Air(), \true, \true);

		return \true;
	}

	public function onActivate(Item $item, Player $player = \null){
		if($player instanceof Player){
			$t = $this->getLevel()->getTile($this);
			$furnace = \false;
			if($t instanceof Furnace){
				$furnace = $t;
			}else{
				$nbt = new CompoundTag("", [
					new EnumTag("Items", []),
					new StringTag("id", Tile::FURNACE),
					new IntTag("x", $this->x),
					new IntTag("y", $this->y),
					new IntTag("z", $this->z)
				]);
				$nbt->Items->setTagType(NBT::TAG_Compound);
				$furnace = Tile::createTile("Furnace", $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4), $nbt);
			}

			if($player->isCreative()){
				return \true;
			}

			$player->addWindow($furnace->getInventory());
		}

		return \true;
	}

	public function getBreakTime(Item $item){
		switch($item->isPickaxe()){
			case 5:
				return 0.7;
			case 4:
				return 0.9;
			case 3:
				return 1.35;
			case 2:
				return 0.45;
			case 1:
				return 2.65;
			default:
				return 17.5;
		}
	}

	public function getDrops(Item $item){
		$drops = [];
		if($item->isPickaxe() >= 1){
			$drops[] = [Item::FURNACE, 0, 1];
		}

		return $drops;
	}
}