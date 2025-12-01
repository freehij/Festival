<?php
namespace pocketmine\item;


class Steak extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::STEAK, 0, $count, "Steak");
	}
}
