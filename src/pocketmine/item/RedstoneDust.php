<?php
namespace pocketmine\item;


class RedstoneDust extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::REDSTONE_DUST, 0, $count, "Redstone dust");
	}
}
