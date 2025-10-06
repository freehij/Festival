<?php


namespace pocketmine\network\protocol;

class LevelEventPacket extends DataPacket{
	const NETWORK_ID = Info::LEVEL_EVENT_PACKET;

	public $evid;
	public $x;
	public $y;
	public $z;
	public $data;

	public function decode(){

	}

	public function encode(){
		$this->buffer = \chr(self::NETWORK_ID); $this->offset = 0;;
		$this->buffer .= \pack("n", $this->evid);
		$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $this->x) : \strrev(\pack("f", $this->x)));
		$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $this->y) : \strrev(\pack("f", $this->y)));
		$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $this->z) : \strrev(\pack("f", $this->z)));
		$this->buffer .= \pack("N", $this->data);
	}

}
