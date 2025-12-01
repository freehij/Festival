<?php
namespace pocketmine\item;


class RawChicken extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::RAW_CHICKEN, 0, $count, "Raw chicken");
	}
}
