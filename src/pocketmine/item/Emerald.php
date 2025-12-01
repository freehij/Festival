<?php
namespace pocketmine\item;


class Emerald extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::EMERALD, 0, $count, "Emerald");
	}
}
