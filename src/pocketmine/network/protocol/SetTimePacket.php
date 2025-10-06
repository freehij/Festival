<?php


namespace pocketmine\network\protocol;


use pocketmine\level\Level;

class SetTimePacket extends DataPacket{
	const NETWORK_ID = Info::SET_TIME_PACKET;

	public $time;
	public $started = \true;

	public function decode(){

	}

	public function encode(){
		$this->buffer = \chr(self::NETWORK_ID); $this->offset = 0;;
		$this->buffer .= \pack("N", (int) (($this->time / Level::TIME_FULL) * 19200));
		$this->buffer .= \chr($this->started == \true ? 0x80 : 0x00);
	}

}
