<?php


namespace pocketmine\level\particle;

use pocketmine\math\Vector3;
use pocketmine\item\Item;

class ItemBreakParticle extends GenericParticle
{

	public function __construct(Vector3 $pos, Item $item)
	{
		parent::__construct($pos, 9, ($item->getId() << 16) | $item->getDamage());
	}
}
