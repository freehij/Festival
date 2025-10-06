<?php


namespace pocketmine\network\protocol;

class AdventureSettingsPacket extends DataPacket{
	const NETWORK_ID = Info::ADVENTURE_SETTINGS_PACKET;

	public $flags;

	public function decode(){

	}

	public function encode(){
		$this->buffer = \chr(self::NETWORK_ID); $this->offset = 0;;
		$this->buffer .= \pack("N", $this->flags);
	}

}
