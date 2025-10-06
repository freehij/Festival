<?php


namespace pocketmine\network\protocol;

class HurtArmorPacket extends DataPacket{
	const NETWORK_ID = Info::HURT_ARMOR_PACKET;

	public $health;

	public function decode(){

	}

	public function encode(){
		$this->buffer = \chr(self::NETWORK_ID); $this->offset = 0;;
		$this->buffer .= \chr($this->health);
	}

}
