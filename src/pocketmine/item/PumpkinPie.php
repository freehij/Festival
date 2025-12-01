<?php
namespace pocketmine\item;


class PumpkinPie extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::PUMPKIN_PIE, 0, $count, "Pumpkin pie");
	}
}
