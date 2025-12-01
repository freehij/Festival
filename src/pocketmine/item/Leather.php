<?php
namespace pocketmine\item;


class Leather extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::LEATHER, 0, $count, "Leather");
	}
}
