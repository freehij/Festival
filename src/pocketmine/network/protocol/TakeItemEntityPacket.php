<?php


namespace pocketmine\network\protocol;

use pocketmine\utils\Binary;











class TakeItemEntityPacket extends DataPacket{
	const NETWORK_ID = Info::TAKE_ITEM_ENTITY_PACKET;

	public $target;
	public $eid;

	public function decode(){

	}

	public function encode(){
		$this->buffer = \chr(self::NETWORK_ID); $this->offset = 0;;
		$this->buffer .= Binary::writeLong($this->target);
		$this->buffer .= Binary::writeLong($this->eid);
	}

}
