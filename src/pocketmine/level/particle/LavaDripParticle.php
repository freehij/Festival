<?php


namespace pocketmine\level\particle;

use pocketmine\math\Vector3;

class LavaDripParticle extends GenericParticle
{

	public function __construct(Vector3 $pos)
	{
		parent::__construct($pos, 21);
	}
}
