<?php
namespace pocketmine\item;


class MelonSlice extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::MELON, 0, $count, "Melon");
	}
}
