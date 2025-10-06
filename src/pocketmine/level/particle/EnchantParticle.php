<?php
//@freehij gib enchantile

namespace pocketmine\level\particle;

use pocketmine\math\Vector3;

class EnchantParticle extends GenericParticle
{

	public function __construct(Vector3 $pos)
	{
		parent::__construct($pos, 23);
	}
}
