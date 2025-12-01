<?php
namespace pocketmine\item;


class Clayball extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::CLAY, 0, $count, "Clayball");
	}
}
