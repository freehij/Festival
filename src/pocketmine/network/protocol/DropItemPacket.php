<?php


namespace pocketmine\network\protocol;

use pocketmine\utils\Binary;











class DropItemPacket extends DataPacket{
	const NETWORK_ID = Info::DROP_ITEM_PACKET;

	public $eid;
	public $unknown;
	public $item;

	public function decode(){
		$this->eid = Binary::readLong($this->get(8));
		$this->unknown = \ord($this->get(1));
		$this->item = $this->getSlot();
	}

	public function encode(){

	}

}
