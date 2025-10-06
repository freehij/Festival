<?php


namespace pocketmine\network\protocol;

use pocketmine\utils\Binary;











class AddEntityPacket extends DataPacket{
	const NETWORK_ID = Info::ADD_ENTITY_PACKET;

	public $eid;
	public $type;
	public $x;
	public $y;
	public $z;
	public $speedX;
	public $speedY;
	public $speedZ;
	public $yaw;
	public $pitch;
	public $metadata;
	public $links = [];

	public function decode(){

	}

	public function encode(){
		$this->buffer = \chr(self::NETWORK_ID); $this->offset = 0;;
		$this->buffer .= Binary::writeLong($this->eid);
		$this->buffer .= \pack("N", $this->type);
		$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $this->x) : \strrev(\pack("f", $this->x)));
		$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $this->y) : \strrev(\pack("f", $this->y)));
		$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $this->z) : \strrev(\pack("f", $this->z)));
		$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $this->speedX) : \strrev(\pack("f", $this->speedX)));
		$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $this->speedY) : \strrev(\pack("f", $this->speedY)));
		$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $this->speedZ) : \strrev(\pack("f", $this->speedZ)));
		$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $this->yaw) : \strrev(\pack("f", $this->yaw)));
		$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $this->pitch) : \strrev(\pack("f", $this->pitch)));
		$meta = Binary::writeMetadata($this->metadata);
		$this->buffer .= $meta;
		$this->buffer .= \pack("n", \count($this->links));
		foreach($this->links as $link){
			$this->buffer .= Binary::writeLong($link[0]);
			$this->buffer .= Binary::writeLong($link[1]);
			$this->buffer .= \chr($link[2]);
		}
	}

}
