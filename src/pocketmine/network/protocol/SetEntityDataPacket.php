<?php


namespace pocketmine\network\protocol;

use pocketmine\utils\Binary;











class SetEntityDataPacket extends DataPacket{
	const NETWORK_ID = Info::SET_ENTITY_DATA_PACKET;

	public $eid;
	public $metadata;

	public function decode(){

	}

	public function encode(){
		$this->buffer = \chr(self::NETWORK_ID); $this->offset = 0;;
		$this->buffer .= Binary::writeLong($this->eid);
		$meta = Binary::writeMetadata($this->metadata);
		$this->buffer .= $meta;
	}

}
