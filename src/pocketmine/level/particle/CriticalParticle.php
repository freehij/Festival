<?php


namespace pocketmine\level\particle;

use pocketmine\math\Vector3;

class CriticalParticle extends GenericParticle
{

	public function __construct(Vector3 $pos, $scale = 2)
	{
		parent::__construct($pos, 2, $scale);
	}
}
