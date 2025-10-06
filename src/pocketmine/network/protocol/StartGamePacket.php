<?php


namespace pocketmine\network\protocol;

use pocketmine\utils\Binary;











class StartGamePacket extends DataPacket{
	const NETWORK_ID = Info::START_GAME_PACKET;

	public $seed;
	public $generator;
	public $gamemode;
	public $eid;
	public $spawnX;
	public $spawnY;
	public $spawnZ;
	public $x;
	public $y;
	public $z;

	public function decode(){

	}

	public function encode(){
		$this->buffer = \chr(self::NETWORK_ID); $this->offset = 0;;
		$this->buffer .= \pack("N", $this->seed);
		$this->buffer .= \pack("N", $this->generator);
		$this->buffer .= \pack("N", $this->gamemode);
		$this->buffer .= Binary::writeLong($this->eid);
		$this->buffer .= \pack("N", $this->spawnX);
		$this->buffer .= \pack("N", $this->spawnY);
		$this->buffer .= \pack("N", $this->spawnZ);
		$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $this->x) : \strrev(\pack("f", $this->x)));
		$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $this->y) : \strrev(\pack("f", $this->y)));
		$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $this->z) : \strrev(\pack("f", $this->z)));
	}

}
