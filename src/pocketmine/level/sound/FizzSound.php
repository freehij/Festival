<?php


namespace pocketmine\level\sound;

use pocketmine\math\Vector3;

class FizzSound extends GenericSound
{

	public function __construct(Vector3 $pos, $pitch = 0)
	{
		parent::__construct($pos, 1004, $pitch);
	}
}
