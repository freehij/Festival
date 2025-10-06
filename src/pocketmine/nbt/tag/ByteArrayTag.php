<?php


namespace pocketmine\nbt\tag;

use pocketmine\nbt\NBT;

class ByteArrayTag extends NamedTag
{

	public function getType()
	{
		return NBT::TAG_ByteArray;
	}

	public function read(NBT $nbt)
	{
		$this->value = $nbt->get($nbt->endianness === 1 ? (\PHP_INT_SIZE === 8 ? \unpack("N", $nbt->get(4))[1] << 32 >> 32 : \unpack("N", $nbt->get(4))[1]) : (\PHP_INT_SIZE === 8 ? \unpack("V", $nbt->get(4))[1] << 32 >> 32 : \unpack("V", $nbt->get(4))[1]));
	}

	public function write(NBT $nbt)
	{
		$nbt->buffer .= $nbt->endianness === 1 ? \pack("N", \strlen($this->value)) : \pack("V", \strlen($this->value));
		$nbt->buffer .= $this->value;
	}
}
