<?php


namespace pocketmine\network\protocol;

class TileEntityDataPacket extends DataPacket{
	const NETWORK_ID = Info::TILE_ENTITY_DATA_PACKET;

	public $x;
	public $y;
	public $z;
	public $namedtag;

	public function decode(){
		$this->x = (\PHP_INT_SIZE === 8 ? \unpack("N", $this->get(4))[1] << 32 >> 32 : \unpack("N", $this->get(4))[1]);
		$this->y = \ord($this->get(1));
		$this->z = (\PHP_INT_SIZE === 8 ? \unpack("N", $this->get(4))[1] << 32 >> 32 : \unpack("N", $this->get(4))[1]);
		$this->namedtag = $this->get(\true);
	}

	public function encode(){
		$this->buffer = \chr(self::NETWORK_ID); $this->offset = 0;;
		$this->buffer .= \pack("N", $this->x);
		$this->buffer .= \chr($this->y);
		$this->buffer .= \pack("N", $this->z);
		$this->buffer .= $this->namedtag;
	}

}
