<?php


namespace pocketmine\network\protocol;

use pocketmine\utils\Binary;











class PlayerEquipmentPacket extends DataPacket{
	const NETWORK_ID = Info::PLAYER_EQUIPMENT_PACKET;

	public $eid;
	public $item;
	public $meta;
	public $slot;
	public $selectedSlot;

	public function decode(){
		$this->eid = Binary::readLong($this->get(8));
		$this->item = \unpack("n", $this->get(2))[1];
		$this->meta = \unpack("n", $this->get(2))[1];
		$this->slot = \ord($this->get(1));
		$this->selectedSlot = \ord($this->get(1));
	}

	public function encode(){
		$this->buffer = \chr(self::NETWORK_ID); $this->offset = 0;;
		$this->buffer .= Binary::writeLong($this->eid);
		$this->buffer .= \pack("n", $this->item);
		$this->buffer .= \pack("n", $this->meta);
		$this->buffer .= \chr($this->slot);
		$this->buffer .= \chr($this->selectedSlot);
	}

}
