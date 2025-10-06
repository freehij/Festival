<?php


namespace pocketmine\network\protocol;

class UpdateBlockPacket extends DataPacket{
	const NETWORK_ID = Info::UPDATE_BLOCK_PACKET;

	const FLAG_NONE	  = 0b0000;
	const FLAG_NEIGHBORS = 0b0001;
	const FLAG_NETWORK   = 0b0010;
	const FLAG_NOGRAPHIC = 0b0100;
	const FLAG_PRIORITY  = 0b1000;

	const FLAG_ALL = (self::FLAG_NEIGHBORS | self::FLAG_NETWORK);
	const FLAG_ALL_PRIORITY = (self::FLAG_ALL | self::FLAG_PRIORITY);

	public $records = []; //x, z, y, blockId, blockData, flags

	public function decode(){

	}

	public function encode(){
		$this->buffer = \chr(self::NETWORK_ID); $this->offset = 0;;
		$this->buffer .= \pack("N", \count($this->records));
		foreach($this->records as $r){
			$this->buffer .= \pack("N", $r[0]);
			$this->buffer .= \pack("N", $r[1]);
			$this->buffer .= \chr($r[2]);
			$this->buffer .= \chr($r[3]);
			$this->buffer .= \chr(($r[5] << 4) | $r[4]);
		}
	}

}
