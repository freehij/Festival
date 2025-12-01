<?php
namespace pocketmine\item;


class BakedPotato extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::BAKED_POTATO, 0, $count, "Baked Potato");
	}
}
