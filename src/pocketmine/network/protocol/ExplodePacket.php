<?php


namespace pocketmine\network\protocol;

class ExplodePacket extends DataPacket{
	const NETWORK_ID = Info::EXPLODE_PACKET;

	public $x;
	public $y;
	public $z;
	public $radius;
	public $records = [];

	public function clean(){
		$this->records = [];
		return parent::clean();
	}

	public function decode(){

	}

	public function encode(){
		$this->buffer = \chr(self::NETWORK_ID); $this->offset = 0;;
		$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $this->x) : \strrev(\pack("f", $this->x)));
		$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $this->y) : \strrev(\pack("f", $this->y)));
		$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $this->z) : \strrev(\pack("f", $this->z)));
		$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $this->radius) : \strrev(\pack("f", $this->radius)));
		$this->buffer .= \pack("N", \count($this->records));
		if(\count($this->records) > 0){
			foreach($this->records as $record){
				$this->buffer .= \chr($record->x);
				$this->buffer .= \chr($record->y);
				$this->buffer .= \chr($record->z);
			}
		}
	}

}
