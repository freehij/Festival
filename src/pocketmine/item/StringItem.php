<?php
namespace pocketmine\item;


class StringItem extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::STRING, 0, $count, "String");
	}
}
