<?php


namespace pocketmine\network\protocol;

use pocketmine\utils\Binary;











class AddItemEntityPacket extends DataPacket{
	const NETWORK_ID = Info::ADD_ITEM_ENTITY_PACKET;

	public $eid;
	public $item;
	public $x;
	public $y;
	public $z;
	public $speedX;
	public $speedY;
	public $speedZ;

	public function decode(){

	}

	public function encode(){
		$this->buffer = \chr(self::NETWORK_ID); $this->offset = 0;;
		$this->buffer .= Binary::writeLong($this->eid);
		$this->putSlot($this->item);
		$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $this->x) : \strrev(\pack("f", $this->x)));
		$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $this->y) : \strrev(\pack("f", $this->y)));
		$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $this->z) : \strrev(\pack("f", $this->z)));
		$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $this->speedX) : \strrev(\pack("f", $this->speedX)));
		$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $this->speedY) : \strrev(\pack("f", $this->speedY)));
		$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $this->speedZ) : \strrev(\pack("f", $this->speedZ)));
	}

}
