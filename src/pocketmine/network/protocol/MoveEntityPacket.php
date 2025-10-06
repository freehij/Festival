<?php


namespace pocketmine\network\protocol;

use pocketmine\utils\Binary;

class MoveEntityPacket extends DataPacket{
	const NETWORK_ID = Info::MOVE_ENTITY_PACKET;


	// eid, x, y, z, yaw, pitch
	/** @var array[] */
	public $entities = [];

	public function clean(){
		$this->entities = [];
		return parent::clean();
	}

	public function decode(){

	}

	public function encode(){
		$this->buffer = \chr(self::NETWORK_ID); $this->offset = 0;;
		$this->buffer .= \pack("N", \count($this->entities));
		foreach($this->entities as $d){
			$this->buffer .= Binary::writeLong($d[0]); //eid
			$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $d[1]) : \strrev(\pack("f", $d[1]))); //x
			$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $d[2]) : \strrev(\pack("f", $d[2]))); //y
			$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $d[3]) : \strrev(\pack("f", $d[3]))); //z
			$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $d[4]) : \strrev(\pack("f", $d[4]))); //yaw
			$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $d[5]) : \strrev(\pack("f", $d[5]))); //headYaw
			$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $d[6]) : \strrev(\pack("f", $d[6]))); //pitch
		}
	}

}
