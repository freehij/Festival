<?php
namespace pocketmine\item;


class RawPorkchop extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::RAW_PORKCHOP, 0, $count, "Raw porkchop");
	}
}
