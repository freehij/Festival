<?php


namespace pocketmine\network\protocol;

use pocketmine\utils\Binary;
use pocketmine\item\Item;


abstract class DataPacket extends \stdClass{

	const NETWORK_ID = 0;

	public $offset = 0;
	public $buffer = "";
	public $isEncoded = \false;
	private $channel = 0;

	public function pid(){
		return $this::NETWORK_ID;
	}

	abstract public function encode();

	abstract public function decode();

	protected function reset(){
		$this->buffer = \chr($this::NETWORK_ID);
		$this->offset = 0;
	}

	public function setChannel($channel){
		$this->channel = (int) $channel;
		return $this;
	}

	public function getChannel(){
		return $this->channel;
	}

	public function setBuffer($buffer = \null, $offset = 0){
		$this->buffer = $buffer;
		$this->offset = (int) $offset;
	}

	public function getOffset(){
		return $this->offset;
	}

	public function getBuffer(){
		return $this->buffer;
	}

	protected function get($len){
		if($len < 0){
			$this->offset = \strlen($this->buffer) - 1;
			return "";
		}elseif($len === \true){
			return \substr($this->buffer, $this->offset);
		}

		return $len === 1 ? $this->buffer[$this->offset++] : \substr($this->buffer, ($this->offset += $len) - $len, $len);
	}

	protected function put($str){
		$this->buffer .= $str;
	}

	protected function getLong(){
		return Binary::readLong($this->get(8));
	}

	protected function putLong($v){
		$this->buffer .= Binary::writeLong($v);
	}

	protected function getInt(){
		return (\PHP_INT_SIZE === 8 ? \unpack("N", $this->get(4))[1] << 32 >> 32 : \unpack("N", $this->get(4))[1]);
	}

	protected function putInt($v){
		$this->buffer .= \pack("N", $v);
	}

	protected function getShort($signed = \true){
		return $signed ? (\PHP_INT_SIZE === 8 ? \unpack("n", $this->get(2))[1] << 48 >> 48 : \unpack("n", $this->get(2))[1] << 16 >> 16) : \unpack("n", $this->get(2))[1];
	}

	protected function putShort($v){
		$this->buffer .= \pack("n", $v);
	}

	protected function getFloat(){
		return (\ENDIANNESS === 0 ? \unpack("f", $this->get(4))[1] : \unpack("f", \strrev($this->get(4)))[1]);
	}

	protected function putFloat($v){
		$this->buffer .= (\ENDIANNESS === 0 ? \pack("f", $v) : \strrev(\pack("f", $v)));
	}

	protected function getTriad(){
		return \unpack("N", "\x00" . $this->get(3))[1];
	}

	protected function putTriad($v){
		$this->buffer .= \substr(\pack("N", $v), 1);
	}


	protected function getLTriad(){
		return \unpack("V", $this->get(3) . "\x00")[1];
	}

	protected function putLTriad($v){
		$this->buffer .= \substr(\pack("V", $v), 0, -1);
	}

	protected function getByte(){
		return \ord($this->buffer[$this->offset++]);
	}

	protected function putByte($v){
		$this->buffer .= \chr($v);
	}

	protected function getDataArray($len = 10){
		$data = [];
		for($i = 1; $i <= $len and !$this->feof(); ++$i){
			$data[] = $this->get(\unpack("N", "\x00" . $this->get(3))[1]);
		}

		return $data;
	}

	protected function putDataArray(array $data = []){
		foreach($data as $v){
			$this->buffer .= \substr(\pack("N", \strlen($v)), 1);
			$this->buffer .= $v;
		}
	}

	protected function getSlot(){
		$id = \unpack("n", $this->get(2))[1];
		$cnt = \ord($this->get(1));

		return Item::get(
			$id,
			\unpack("n", $this->get(2))[1],
			$cnt
		);
	}

	protected function putSlot(Item $item){
		$this->buffer .= \pack("n", $item->getId());
		$this->buffer .= \chr($item->getCount());
		$this->buffer .= \pack("n", $item->getDamage());
	}

	protected function getString(){
		return $this->get(\unpack("n", $this->get(2))[1]);
	}

	protected function putString($v){
		$this->buffer .= \pack("n", \strlen($v));
		$this->buffer .= $v;
	}

	protected function feof(){
		return !isset($this->buffer[$this->offset]);
	}

	public function clean(){
		$this->buffer = \null;
		$this->isEncoded = \false;
		$this->offset = 0;
		return $this;
	}
}
