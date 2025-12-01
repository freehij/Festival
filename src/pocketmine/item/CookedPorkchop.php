<?php
namespace pocketmine\item;


class CookedPorkchop extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::COOKED_PORKCHOP, 0, $count, "Cooked porkchop");
	}
}
