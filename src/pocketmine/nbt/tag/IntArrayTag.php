<?php


namespace pocketmine\nbt\tag;

use pocketmine\nbt\NBT;

class IntArrayTag extends NamedTag
{

	public function getType()
	{
		return NBT::TAG_IntArray;
	}

	public function read(NBT $nbt)
	{
		$this->value = [];
		$size = $nbt->endianness === 1 ? (\PHP_INT_SIZE === 8 ? \unpack("N", $nbt->get(4))[1] << 32 >> 32 : \unpack("N", $nbt->get(4))[1]) : (\PHP_INT_SIZE === 8 ? \unpack("V", $nbt->get(4))[1] << 32 >> 32 : \unpack("V", $nbt->get(4))[1]);
		$value = \unpack($nbt->endianness === NBT::LITTLE_ENDIAN ? "V*" : "N*", $nbt->get($size * 4));
		foreach ($value as $i => $v) {
			$this->value[$i - 1] = $v;
		}
	}

	public function write(NBT $nbt)
	{
		$nbt->buffer .= $nbt->endianness === 1 ? \pack("N", \count($this->value)) : \pack("V", \count($this->value));
		$nbt->buffer .= \pack($nbt->endianness === NBT::LITTLE_ENDIAN ? "V*" : "N*", ...$this->value);
	}

	public function __toString()
	{
		$str = \get_class($this) . "{\n";
		$str .= \implode(", ", $this->value);
		return $str . "}";
	}
}
