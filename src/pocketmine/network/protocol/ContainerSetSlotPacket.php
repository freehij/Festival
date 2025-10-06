<?php


namespace pocketmine\network\protocol;

use pocketmine\item\Item;

class ContainerSetSlotPacket extends DataPacket{
	const NETWORK_ID = Info::CONTAINER_SET_SLOT_PACKET;

	public $windowid;
	public $slot;
	/** @var Item */
	public $item;

	public function decode(){
		$this->windowid = \ord($this->get(1));
		$this->slot = \unpack("n", $this->get(2))[1];
		$this->item = $this->getSlot();
	}

	public function encode(){
		$this->buffer = \chr(self::NETWORK_ID); $this->offset = 0;;
		$this->buffer .= \chr($this->windowid);
		$this->buffer .= \pack("n", $this->slot);
		$this->putSlot($this->item);
	}

}
