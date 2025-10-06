<?php


namespace pocketmine\network\protocol;

use pocketmine\utils\Binary;

class RemoveEntityPacket extends DataPacket{
	const NETWORK_ID = Info::REMOVE_ENTITY_PACKET;

	public $eid;

	public function decode(){

	}

	public function encode(){
		$this->buffer = \chr(self::NETWORK_ID); $this->offset = 0;;
		$this->buffer .= Binary::writeLong($this->eid);
	}

}
