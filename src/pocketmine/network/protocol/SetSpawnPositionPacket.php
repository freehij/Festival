<?php


namespace pocketmine\network\protocol;

class SetSpawnPositionPacket extends DataPacket{
	const NETWORK_ID = Info::SET_SPAWN_POSITION_PACKET;

	public $x;
	public $z;
	public $y;

	public function decode(){

	}

	public function encode(){
		$this->buffer = \chr(self::NETWORK_ID); $this->offset = 0;;
		$this->buffer .= \pack("N", $this->x);
		$this->buffer .= \pack("N", $this->z);
		$this->buffer .= \chr($this->y);
	}

}
