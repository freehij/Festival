<?php


namespace pocketmine\network\protocol;

use pocketmine\utils\Binary;











class PlayerArmorEquipmentPacket extends DataPacket{
	const NETWORK_ID = Info::PLAYER_ARMOR_EQUIPMENT_PACKET;

	public $eid;
	public $slots = [];

	public function decode(){
		$this->eid = Binary::readLong($this->get(8));
		$this->slots[0] = \ord($this->get(1));
		$this->slots[1] = \ord($this->get(1));
		$this->slots[2] = \ord($this->get(1));
		$this->slots[3] = \ord($this->get(1));
	}

	public function encode(){
		$this->buffer = \chr(self::NETWORK_ID); $this->offset = 0;;
		$this->buffer .= Binary::writeLong($this->eid);
		$this->buffer .= \chr($this->slots[0]);
		$this->buffer .= \chr($this->slots[1]);
		$this->buffer .= \chr($this->slots[2]);
		$this->buffer .= \chr($this->slots[3]);
	}

}
