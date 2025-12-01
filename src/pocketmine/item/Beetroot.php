<?php
namespace pocketmine\item;


class Beetroot extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::BEETROOT, 0, $count, "Beetroot");
	}
}
