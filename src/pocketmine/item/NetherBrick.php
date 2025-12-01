<?php
namespace pocketmine\item;


class NetherBrick extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::NETHER_BRICK, 0, $count, "Nether brick");
	}
}
