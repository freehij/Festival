<?php
namespace pocketmine\item;


class Quartz extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::QUARTZ, 0, $count, "Quartz");
	}
}
