<?php
namespace pocketmine\item;


class MinecartItem extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::MINECART, 0, $count, "Minecart");
	}
}
