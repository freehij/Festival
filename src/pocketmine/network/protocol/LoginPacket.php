<?php


namespace pocketmine\network\protocol;

class LoginPacket extends DataPacket{
	const NETWORK_ID = Info::LOGIN_PACKET;

	public $username;
	public $protocol1;
	public $protocol2;
	public $clientId;

	public $slim = \false;
	public $skin = \null;

	public function decode(){
		$this->username = $this->getString();
		$this->protocol1 = (\PHP_INT_SIZE === 8 ? \unpack("N", $this->get(4))[1] << 32 >> 32 : \unpack("N", $this->get(4))[1]);
		$this->protocol2 = (\PHP_INT_SIZE === 8 ? \unpack("N", $this->get(4))[1] << 32 >> 32 : \unpack("N", $this->get(4))[1]);
		$this->clientId = (\PHP_INT_SIZE === 8 ? \unpack("N", $this->get(4))[1] << 32 >> 32 : \unpack("N", $this->get(4))[1]);
		if($this->protocol1 < 21){ //New fields!
			$this->setBuffer(\null, 0); //Skip batch packet handling
			return;
		}
		$this->slim = \ord($this->get(1)) > 0;
		$this->skin = $this->getString();
	}

	public function encode(){

	}

}
