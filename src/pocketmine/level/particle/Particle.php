<?php


namespace pocketmine\level\particle;

use pocketmine\math\Vector3;
use pocketmine\network\protocol\DataPacket;

abstract class Particle extends Vector3
{

	/**
	 *
	 * @return DataPacket|DataPacket[]
	 */
	abstract public function encode();
}
