<?php


namespace pocketmine\network\protocol;

use pocketmine\utils\Binary;

class MobEffectPacket extends DataPacket{
	const NETWORK_ID = Info::MOB_EFFECT_PACKET;

	const EVENT_ADD = 1;
	const EVENT_MODIFY = 2;
	const EVENT_REMOVE = 3;

	public $eid;
	public $eventId = 0;
	public $effectId = 0;
	public $amplifier = 0;
	public $particles = \true;
	public $duration = 0;

	public function decode(){

	}

	public function encode(){
		$this->buffer = \chr(self::NETWORK_ID);
		$this->offset = 0;;
		$this->buffer .= Binary::writeLong($this->eid);
		$this->buffer .= \chr($this->eventId);
		$this->buffer .= \chr($this->effectId);
		$this->buffer .= \chr($this->amplifier);
		$this->buffer .= \chr($this->particles ? 1 : 0);
		$this->buffer .= \pack("N", $this->duration);
	}

}
