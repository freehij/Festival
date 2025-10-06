<?php


namespace pocketmine\network\protocol;


class ContainerClosePacket extends DataPacket{
	const NETWORK_ID = Info::CONTAINER_CLOSE_PACKET;

	public $windowid;

	public function decode(){
		$this->windowid = \ord($this->get(1));
	}

	public function encode(){
		$this->buffer = \chr(self::NETWORK_ID); $this->offset = 0;;
		$this->buffer .= \chr($this->windowid);
	}

}
