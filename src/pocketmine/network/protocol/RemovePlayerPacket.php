<?php


namespace pocketmine\network\protocol;

use pocketmine\utils\Binary;











class RemovePlayerPacket extends DataPacket{
	const NETWORK_ID = Info::REMOVE_PLAYER_PACKET;

	public $eid;
	public $clientID;

	public function decode(){

	}

	public function encode(){
		$this->buffer = \chr(self::NETWORK_ID); $this->offset = 0;;
		$this->buffer .= Binary::writeLong($this->eid);
		$this->buffer .= Binary::writeLong($this->clientID);
	}

}
