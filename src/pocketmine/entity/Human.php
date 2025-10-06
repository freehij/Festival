<?php


namespace pocketmine\entity;

use pocketmine\inventory\InventoryHolder;
use pocketmine\inventory\PlayerInventory;
use pocketmine\item\Item as ItemItem;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\EnumTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\Network;
use pocketmine\network\protocol\AddPlayerPacket;
use pocketmine\network\protocol\RemovePlayerPacket;
use pocketmine\Player;
use  pocketmine\math\Vector3;
class Human extends Creature implements ProjectileSource, InventoryHolder{

	const DATA_PLAYER_FLAG_SLEEP = 1;
	const DATA_PLAYER_FLAG_DEAD = 2;

	const DATA_PLAYER_FLAGS = 16;
	const DATA_PLAYER_BED_POSITION = 17;

	/** @var PlayerInventory */
	protected $inventory;

	public $width = 0.6;
	public $height = 1.8;
	public $eyeHeight = 1.62;

	protected $skin;
	protected $isSlim = \false;

	public function getSkinData(){
		return $this->skin;
	}

	public function isSkinSlim(){
		return $this->isSlim;
	}
	public function fall($fallDistance){
		parent::fall($fallDistance);
		$x = floor($this->x);
		$y = floor($this->y - 0.2);
		$z = floor($this->z);
		$block = $this->level->getBlock(new Vector3($x, $y, $z));	
		if($block->getId() > 0){
			$block->onFall($this, $fallDistance);
		}
	}
	/**
	 * @param string $str
	 * @param bool   $isSlim
	 */
	public function setSkin($str, $isSlim = \false){
		$this->skin = $str;
		$this->isSlim = (bool) $isSlim;
	}

	public function getInventory(){
		return $this->inventory;
	}

	protected function initEntity(){

		$this->setDataFlag(self::DATA_PLAYER_FLAGS, self::DATA_PLAYER_FLAG_SLEEP, \false);
		$this->setDataProperty(self::DATA_PLAYER_BED_POSITION, self::DATA_TYPE_POS, [0, 0, 0]);

		$this->inventory = new PlayerInventory($this);
		if($this instanceof Player){
			$this->addWindow($this->inventory, 0);
		}


		if(!($this instanceof Player)){
			if(isset($this->namedtag->NameTag)){
				$this->setNameTag($this->namedtag["NameTag"]);
			}

			if(isset($this->namedtag->Skin) and $this->namedtag->Skin instanceof CompoundTag){
				$this->setSkin($this->namedtag->Skin["Data"], $this->namedtag->Skin["Slim"] > 0);
			}
		}

		if(isset($this->namedtag->Inventory) and $this->namedtag->Inventory instanceof EnumTag){
			foreach($this->namedtag->Inventory as $item){
				if($item["Slot"] >= 0 and $item["Slot"] < 9){ //Hotbar
					$this->inventory->setHotbarSlotIndex($item["Slot"], isset($item["TrueSlot"]) ? $item["TrueSlot"] : -1);
				}elseif($item["Slot"] >= 100 and $item["Slot"] < 104){ //Armor
					$this->inventory->setItem($this->inventory->getSize() + $item["Slot"] - 100, ItemItem::get($item["id"], $item["Damage"], $item["Count"]));
				}else{
					$this->inventory->setItem($item["Slot"] - 9, ItemItem::get($item["id"], $item["Damage"], $item["Count"]));
				}
			}
		}

		parent::initEntity();
	}

	public function getName(){
		return $this->getNameTag();
	}

	public function getDrops(){
		$drops = [];
		if($this->inventory !== \null){
			foreach($this->inventory->getContents() as $item){
				$drops[] = $item;
			}
		}

		return $drops;
	}

	public function saveNBT(){
		parent::saveNBT();
		$this->namedtag->Inventory = new EnumTag("Inventory", []);
		$this->namedtag->Inventory->setTagType(NBT::TAG_Compound);
		if($this->inventory !== \null){
			for($slot = 0; $slot < 9; ++$slot){
				$hotbarSlot = $this->inventory->getHotbarSlotIndex($slot);
				if($hotbarSlot !== -1){
					$item = $this->inventory->getItem($hotbarSlot);
					if($item->getId() !== 0 and $item->getCount() > 0){
						$this->namedtag->Inventory[$slot] = new CompoundTag("", [
							new ByteTag("Count", $item->getCount()),
							new ShortTag("Damage", $item->getDamage()),
							new ByteTag("Slot", $slot),
							new ByteTag("TrueSlot", $hotbarSlot),
							new ShortTag("id", $item->getId()),
						]);
						continue;
					}
				}
				$this->namedtag->Inventory[$slot] = new CompoundTag("", [
					new ByteTag("Count", 0),
					new ShortTag("Damage", 0),
					new ByteTag("Slot", $slot),
					new ByteTag("TrueSlot", -1),
					new ShortTag("id", 0),
				]);
			}

			//Normal inventory
			$slotCount = Player::SURVIVAL_SLOTS + 9;
			//$slotCount = (($this instanceof Player and ($this->gamemode & 0x01) === 1) ? Player::CREATIVE_SLOTS : Player::SURVIVAL_SLOTS) + 9;
			for($slot = 9; $slot < $slotCount; ++$slot){
				$item = $this->inventory->getItem($slot - 9);
				$this->namedtag->Inventory[$slot] = new CompoundTag("", [
					new ByteTag("Count", $item->getCount()),
					new ShortTag("Damage", $item->getDamage()),
					new ByteTag("Slot", $slot),
					new ShortTag("id", $item->getId()),
				]);
			}

			//Armor
			for($slot = 100; $slot < 104; ++$slot){
				$item = $this->inventory->getItem($this->inventory->getSize() + $slot - 100);
				if($item instanceof ItemItem and $item->getId() !== ItemItem::AIR){
					$this->namedtag->Inventory[$slot] = new CompoundTag("", [
						new ByteTag("Count", $item->getCount()),
						new ShortTag("Damage", $item->getDamage()),
						new ByteTag("Slot", $slot),
						new ShortTag("id", $item->getId()),
					]);
				}
			}
		}

		if(\strlen($this->getSkinData()) > 0){
			$this->namedtag->Skin = new CompoundTag("Skin", [
				"Data" => new StringTag("Data", $this->getSkinData()),
				"Slim" => new ByteTag("Slim", $this->isSkinSlim() ? 1 : 0)
			]);
		}
	}

	public function spawnTo(Player $player){
		if($player !== $this and !isset($this->hasSpawned[$player->getLoaderId()])){
			$this->hasSpawned[$player->getLoaderId()] = $player;

			if(\strlen($this->skin) < 64 * 32 * 4){
				throw new \InvalidStateException((new \ReflectionClass($this))->getShortName() . " must have a valid skin set");
			}

			$pk = new AddPlayerPacket();
			$pk->clientID = $this->getId();
			$pk->username = $this->getName();
			$pk->eid = $this->getId();
			$pk->x = $this->x;
			$pk->y = $this->y;
			$pk->z = $this->z;
			$pk->speedX = $this->motionX;
			$pk->speedY = $this->motionY;
			$pk->speedZ = $this->motionZ;
			$pk->yaw = $this->yaw;
			$pk->pitch = $this->pitch;
			$item = $this->getInventory()->getItemInHand();
			$pk->item = $item->getId();
			$pk->meta = $item->getDamage();
			$pk->skin = $this->skin;
			$pk->slim = $this->isSlim;
			$pk->metadata = $this->dataProperties;
			$player->dataPacket($pk->setChannel(Network::CHANNEL_ENTITY_SPAWNING));

			$this->inventory->sendArmorContents($player);
		}
	}

	public function despawnFrom(Player $player){
		if(isset($this->hasSpawned[$player->getLoaderId()])){
			$pk = new RemovePlayerPacket();
			$pk->eid = $this->getId();
			$pk->clientID = $this->getId();
			$player->dataPacket($pk->setChannel(Network::CHANNEL_ENTITY_SPAWNING));
			unset($this->hasSpawned[$player->getLoaderId()]);
		}
	}

	public function close(){
		if(!$this->closed){
			if(!($this instanceof Player) or $this->loggedIn){
				foreach($this->inventory->getViewers() as $viewer){
					$viewer->removeWindow($this->inventory);
				}
			}
			parent::close();
		}
	}

}
