<?php


namespace pocketmine\level\particle;

use pocketmine\network\protocol\LevelEventPacket;
use pocketmine\math\Vector3;

class MobSpawnParticle extends Particle
{

	protected $width;

	protected $height;

	public function __construct(Vector3 $pos, $width = 0, $height = 0)
	{
		parent::__construct($pos->x, $pos->y, $pos->z);
		$this->width = $width;
		$this->height = $height;
	}

	public function encode()
	{
		$pk = new LevelEventPacket();
		$pk->evid = 2004;
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->data = ($this->width & 0xff) + (($this->height & 0xff) << 8);

		return $pk;
	}
}
