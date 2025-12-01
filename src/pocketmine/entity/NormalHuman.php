<?php
namespace pocketmine\entity;
use pocketmine\level\format\FullChunk;
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
use pocketmine\math\Vector3;

class NormalHuman extends Human {

    public $width = 0.6;
    public $height = 1.8;

    public function getName(): string {
        return "Human";
    }

    public function __construct(FullChunk $chunk, CompoundTag $nbt) {
        parent::__construct($chunk, $nbt);
    }

    protected function initEntity(): void {
        parent::initEntity();
        
        $this->setMaxHealth(20);
        $this->setHealth(20);
        $this->setNameTag($this->getName());
        
        $skin = $this->defaultSkin();
        $this->setSkin($skin);
    }

    private function defaultSkin(): string {
        $skin = "";
        for($i = 0; $i < 64 * 64; $i++) {
            $skin .= chr(204) . chr(179) . chr(153) . chr(255);
        }
        return $skin;
    }

    public function getDrops(): array {
        return [];
    }
}
