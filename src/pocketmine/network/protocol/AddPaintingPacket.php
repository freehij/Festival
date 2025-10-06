<?php


namespace pocketmine\network\protocol;

use pocketmine\utils\Binary;











class AddPaintingPacket extends DataPacket{
	const NETWORK_ID = Info::ADD_PAINTING_PACKET;

	public $eid;
	public $x;
	public $y;
	public $z;
	public $direction;
	public $title;

	public function decode(){

	}

	public function encode(){
		$this->buffer = \chr(self::NETWORK_ID); $this->offset = 0;;
		$this->buffer .= Binary::writeLong($this->eid);
		$this->buffer .= \pack("N", $this->x);
		$this->buffer .= \pack("N", $this->y);
		$this->buffer .= \pack("N", $this->z);
		$this->buffer .= \pack("N", $this->direction);
		$this->putString($this->title);
	}

}
