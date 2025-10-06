<?php


namespace pocketmine\network\protocol;

class FullChunkDataPacket extends DataPacket{
	const NETWORK_ID = Info::FULL_CHUNK_DATA_PACKET;

	public $chunkX;
	public $chunkZ;
	public $data;

	public function decode(){

	}

	public function encode(){
		$this->buffer = \chr(self::NETWORK_ID); $this->offset = 0;;
		$this->buffer .= \pack("N", $this->chunkX);
		$this->buffer .= \pack("N", $this->chunkZ);
		$this->buffer .= \pack("N", \strlen($this->data));
		$this->buffer .= $this->data;
	}

}
