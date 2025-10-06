<?php
namespace pocketmine\entity\projectile;

use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\level\format\FullChunk;
use pocketmine\level\particle\BubbleParticle;
use pocketmine\level\particle\SplashParticle;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\protocol\EntityEventPacket;
use pocketmine\network\protocol\SetEntityLinkPacket;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\entity\Projectile;

class FishingHook extends Projectile
{
    const NETWORK_ID = 77;

    public $width = 0.25;
    public $length = 0.25;
    public $height = 0.25;

    protected $gravity = 0.1;
    protected $drag = 0.05;

    private $fishWaitTimer = 0;
    private $attractTimer = 0;
    private $downTimer = 0;
    private $attracted = false;

    public function __construct(FullChunk $chunk, CompoundTag $nbt, Entity $shootingEntity = null)
    {
        parent::__construct($chunk, $nbt, $shootingEntity);
        if ($this->shootingEntity instanceof Player) {
            $this->shootingEntity->fishingHook = $this;
            $this->setDataProperty(self::DATA_OWNER_EID, self::DATA_TYPE_LONG, $shootingEntity->getId());
        }
    }


    public function spawnTo(Player $player) {
        parent::spawnTo($player);

        if ($this->shootingEntity instanceof Player) {
            $pk = new SetEntityLinkPacket();
            $pk->from = $this->shootingEntity->getId();
            $pk->to = $this->getId();
            $pk->type = 1;
            $player->dataPacket($pk);
        }
    }

    public function onUpdate($currentTick)
    {
        $hasUpdate = parent::onUpdate($currentTick);

        if ($this->isInsideOfWater()) {
            $this->motionY = 0.04;

            $this->fishWaitTimer++;
            if ($this->fishWaitTimer > 200 && !$this->attracted) {
                $this->attractFish();
            }

            if ($this->attracted) {
                $this->attractTimer++;
                $this->level->addParticle(new BubbleParticle($this->add(mt_rand(-10, 10) / 100, -0.1, mt_rand(-10, 10) / 100)));
                if ($this->attractTimer > 100) {
                    $this->fishBites();
                }
            }

            if ($this->downTimer > 0) {
                $this->downTimer++;
                if ($this->downTimer > 10) {
                    $this->downTimer = 0;
                    $this->attracted = false;
                    $this->attractTimer = 0;
                }
            }
        }

        return $hasUpdate;
    }

    public function attractFish()
    {
        $this->attracted = true;
        $this->fishWaitTimer = 0;
    }

    public function fishBites()
    {
        $this->downTimer = 1;
        $pk = new EntityEventPacket();
        $pk->eid = $this->getId();
        $pk->event = EntityEventPacket::FISH_HOOK_BITE;
        Server::getInstance()->broadcastPacket($this->hasSpawned, $pk);
        $this->level->addParticle(new SplashParticle($this));
    }

    public function reelLine()
    {
        if ($this->shootingEntity instanceof Player && $this->isAlive()) {
            if ($this->downTimer > 0) {
                $this->catchFish();
            }
            $this->close();
        }
    }

    public function close(){
        parent::close();
        if($this->shootingEntity instanceof Player){
            $this->shootingEntity->fishingHook = null;

            $pk = new SetEntityLinkPacket();
            $pk->from = $this->shootingEntity->getId();
            $pk->to = 0;
            $pk->type = 1;
            Server::getInstance()->broadcastPacket($this->getViewers(), $pk);
        }
    }

    public function catchFish()
    {

        $chance = mt_rand(1, 1000) / 10;
        $item = null;
        if ($chance <= 85) {
            $fishType = mt_rand(1, 100);
            if ($fishType <= 60) $item = Item::get(Item::RAW_FISH, 0, 1); // Pescado Crudo
            elseif ($fishType <= 85) $item = Item::get(Item::RAW_SALMON, 0, 1); // Salmón Crudo
            elseif ($fishType <= 98) $item = Item::get(Item::PUFFERFISH, 0, 1); // Pez Globo
            else $item = Item::get(Item::CLOWNFISH, 0, 1); // Pez Payaso
        } elseif ($chance <= 95) {
            $junkType = mt_rand(1, 100);
            if ($junkType <= 17) $item = Item::get(Item::LEATHER_BOOTS, 0, 1);
            elseif ($junkType <= 34) $item = Item::get(Item::LEATHER, 0, 1);
            elseif ($junkType <= 51) $item = Item::get(Item::BONE, 0, 1);

            else $item = Item::get(Item::ROTTEN_FLESH, 0, 1);
        } else {

            $item = Item::get(Item::NAME_TAG, 0, 1);
        }

        if ($item !== null) {
            $motion = $this->shootingEntity->subtract($this)->multiply(0.1);
            $this->level->dropItem($this, $item, $motion);
        }
    }
}