<?php
namespace pocketmine\item;


class CookedChicken extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::COOKED_CHICKEN, 0, $count, "Cooked chicken");
	}
}
