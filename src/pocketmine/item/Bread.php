<?php
namespace pocketmine\item;


class Bread extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::BREAD, 0, $count, "Bread");
	}
}
