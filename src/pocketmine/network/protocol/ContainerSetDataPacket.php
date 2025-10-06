<?php


namespace pocketmine\network\protocol;

class ContainerSetDataPacket extends DataPacket{
	const NETWORK_ID = Info::CONTAINER_SET_DATA_PACKET;

	public $windowid;
	public $property;
	public $value;

	public function decode(){

	}

	public function encode(){
		$this->buffer = \chr(self::NETWORK_ID); $this->offset = 0;;
		$this->buffer .= \chr($this->windowid);
		$this->buffer .= \pack("n", $this->property);
		$this->buffer .= \pack("n", $this->value);
	}

}
