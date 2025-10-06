<?php
namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\entity\projectile\FishingHook;
use pocketmine\level\Level;
use pocketmine\level\sound\LaunchSound;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\EnumTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\Player;

class FishingRod extends Item
{
    public function __construct($meta = 0, $count = 1)
    {
        parent::__construct(self::FISHING_ROD, $meta, $count, "Fishing Rod");
    }

    public function getMaxStackSize()
    {
        return 1;
    }

    public function canBeActivated(): bool
    {
        return true;
    }

    public function onActivate(Level $level, Player $player, ?Block $block, ?Block $target, $face, $fx, $fy, $fz)
    {

        if ($player->fishingHook instanceof FishingHook && !$player->fishingHook->closed) {
            $player->fishingHook->reelLine();
            $player->fishingHook = null;
            return true;
        }


        $nbt = new CompoundTag("", [
            "Pos" => new EnumTag("Pos", [
                new DoubleTag("", $player->x),
                new DoubleTag("", $player->y + $player->getEyeHeight()),
                new DoubleTag("", $player->z)
            ]),
            "Motion" => new EnumTag("Motion", [
                new DoubleTag("", -sin($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI)),
                new DoubleTag("", -sin($player->pitch / 180 * M_PI)),
                new DoubleTag("", cos($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI))
            ]),
            "Rotation" => new EnumTag("Rotation", [
                new FloatTag("", $player->yaw),
                new FloatTag("", $player->pitch)
            ]),
        ]);

        $f = 1.2;
        $fishingHook = Entity::createEntity("FishingHook", $player->chunk, $nbt, $player);

        if ($fishingHook instanceof FishingHook) {
            $fishingHook->setMotion($fishingHook->getMotion()->multiply($f));
            $fishingHook->spawnToAll();
            $player->fishingHook = $fishingHook;
            $player->getLevel()->addSound(new LaunchSound($player), $player->getViewers());
        }

        return true;
    }
}