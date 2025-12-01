<?php
namespace pocketmine\item;


class Slimeball extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::SLIMEBALL, 0, $count, "Slimeball");
	}
}
