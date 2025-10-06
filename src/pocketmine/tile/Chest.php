<?php


namespace pocketmine\tile;

use pocketmine\inventory\ChestInventory;
use pocketmine\inventory\DoubleChestInventory;
use pocketmine\inventory\InventoryHolder;
use pocketmine\item\Item;
use pocketmine\level\format\FullChunk;
use pocketmine\math\Vector3;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\EnumTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\StringTag;

class Chest extends Spawnable implements InventoryHolder, Container{

	/** @var ChestInventory */
	protected $inventory;
	/** @var DoubleChestInventory */
	protected $doubleInventory = \null;

	public function __construct(FullChunk $chunk, CompoundTag $nbt){
		parent::__construct($chunk, $nbt);
		$this->inventory = new ChestInventory($this);

		if(!isset($this->namedtag->Items) or !($this->namedtag->Items instanceof EnumTag)){
			$this->namedtag->Items = new EnumTag("Inventory", []);
			$this->namedtag->Items->setTagType(NBT::TAG_Compound);
		}

		for($i = 0; $i < $this->getSize(); ++$i){
			$this->inventory->setItem($i, $this->getItem($i));
		}
	}

	public function close(){
		if($this->closed === \false){
			foreach($this->getInventory()->getViewers() as $player){
				$player->removeWindow($this->getInventory());
			}

			foreach($this->getInventory()->getViewers() as $player){
				$player->removeWindow($this->getRealInventory());
			}
			parent::close();
		}
	}

	public function saveNBT(){
		$this->namedtag->Items = new EnumTag("Items", []);
		$this->namedtag->Items->setTagType(NBT::TAG_Compound);
		for($index = 0; $index < $this->getSize(); ++$index){
			$this->setItem($index, $this->inventory->getItem($index));
		}
	}

	/**
	 * @return int
	 */
	public function getSize(){
		return 27;
	}

	/**
	 * @param $index
	 *
	 * @return int
	 */
	protected function getSlotIndex($index){
		foreach($this->namedtag->Items as $i => $slot){
			if($slot["Slot"] === $index){
				return $i;
			}
		}

		return -1;
	}

	/**
	 * This method should not be used by plugins, use the Inventory
	 *
	 * @param int $index
	 *
	 * @return Item
	 */
	public function getItem($index){
		$i = $this->getSlotIndex($index);
		if($i < 0){
			return Item::get(Item::AIR, 0, 0);
		}else{
			return Item::get($this->namedtag->Items[$i]["id"], $this->namedtag->Items[$i]["Damage"], $this->namedtag->Items[$i]["Count"]);
		}
	}

	/**
	 * This method should not be used by plugins, use the Inventory
	 *
	 * @param int  $index
	 * @param Item $item
	 *
	 * @return bool
	 */
	public function setItem($index, Item $item){
		$i = $this->getSlotIndex($index);

		$d = new CompoundTag("", [
			new ByteTag("Count", $item->getCount()),
			new ByteTag("Slot", $index),
			new ShortTag("id", $item->getId()),
			new ShortTag("Damage", $item->getDamage()),
		]);

		if($item->getId() === Item::AIR or $item->getCount() <= 0){
			if($i >= 0){
				unset($this->namedtag->Items[$i]);
			}
		}elseif($i < 0){
			for($i = 0; $i <= $this->getSize(); ++$i){
				if(!isset($this->namedtag->Items[$i])){
					break;
				}
			}
			$this->namedtag->Items[$i] = $d;
		}else{
			$this->namedtag->Items[$i] = $d;
		}

		return \true;
	}

	/**
	 * @return ChestInventory|DoubleChestInventory
	 */
	public function getInventory(){
		if($this->isPaired() and $this->doubleInventory === \null){
			$this->checkPairing();
		}
		return $this->doubleInventory instanceof DoubleChestInventory ? $this->doubleInventory : $this->inventory;
	}

	/**
	 * @return ChestInventory
	 */
	public function getRealInventory(){
		return $this->inventory;
	}

	protected function checkPairing(){
		if($this->isPaired() and !$this->getLevel()->isChunkLoaded($this->namedtag->pairx->getValue() >> 4, $this->namedtag->pairz->getValue() >> 4)){
			//paired to a tile in an unloaded chunk
			$this->doubleInventory = null;
		}elseif(($pair = $this->getPair()) instanceof Chest){
			if(!$pair->isPaired()){
				$pair->createPair($this);
				$pair->checkPairing();
			}
			if($this->doubleInventory === \null){
				if(($pair->x + ($pair->z << 15)) > ($this->x + ($this->z << 15))){ //Order them correctly
					$this->doubleInventory = new DoubleChestInventory($pair, $this);
				}else{
					$this->doubleInventory = new DoubleChestInventory($this, $pair);
				}
			}
		}else{
			$this->doubleInventory = \null;
			unset($this->namedtag->pairx, $this->namedtag->pairz);
		}
	}

	public function isPaired(){
		if(!isset($this->namedtag->pairx) or !isset($this->namedtag->pairz)){
			return \false;
		}

		return \true;
	}

	/**
	 * @return Chest
	 */
	public function getPair(){
		if($this->isPaired()){
			$tile = $this->getLevel()->getTile(new Vector3((int) $this->namedtag["pairx"], $this->y, (int) $this->namedtag["pairz"]));
			if($tile instanceof Chest){
				return $tile;
			}
		}

		return \null;
	}

	public function pairWith(Chest $tile){
		if($this->isPaired() or $tile->isPaired()){
			return \false;
		}

		$this->createPair($tile);

		$this->spawnToAll();
		$tile->spawnToAll();
		$this->checkPairing();

		return \true;
	}

	private function createPair(Chest $tile){
		$this->namedtag->pairx = new IntTag("pairx", $tile->x);
		$this->namedtag->pairz = new IntTag("pairz", $tile->z);

		$tile->namedtag->pairx = new IntTag("pairx", $this->x);
		$tile->namedtag->pairz = new IntTag("pairz", $this->z);
	}

	public function unpair(){
		if(!$this->isPaired()){
			return \false;
		}

		$tile = $this->getPair();
		unset($this->namedtag->pairx, $this->namedtag->pairz);

		$this->spawnToAll();

		if($tile instanceof Chest){
			unset($tile->namedtag->pairx, $tile->namedtag->pairz);
			$tile->checkPairing();
			$tile->spawnToAll();
		}
		$this->checkPairing();

		return \true;
	}

	public function getSpawnCompound(){
		if($this->isPaired()){
			return new CompoundTag("", [
				new StringTag("id", Tile::CHEST),
				new IntTag("x", (int) $this->x),
				new IntTag("y", (int) $this->y),
				new IntTag("z", (int) $this->z),
				new IntTag("pairx", (int) $this->namedtag["pairx"]),
				new IntTag("pairz", (int) $this->namedtag["pairz"])
			]);
		}else{
			return new CompoundTag("", [
				new StringTag("id", Tile::CHEST),
				new IntTag("x", (int) $this->x),
				new IntTag("y", (int) $this->y),
				new IntTag("z", (int) $this->z)
			]);
		}
	}
}
