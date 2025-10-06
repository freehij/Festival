<?php


namespace pocketmine\nbt\tag;

use pocketmine\nbt\NBT;

class IntTag extends NamedTag
{

	public function getType()
	{
		return NBT::TAG_Int;
	}

	public function read(NBT $nbt)
	{
		$this->value = $nbt->endianness === 1 ? (\PHP_INT_SIZE === 8 ? \unpack("N", $nbt->get(4))[1] << 32 >> 32 : \unpack("N", $nbt->get(4))[1]) : (\PHP_INT_SIZE === 8 ? \unpack("V", $nbt->get(4))[1] << 32 >> 32 : \unpack("V", $nbt->get(4))[1]);
	}

	public function write(NBT $nbt)
	{
		$nbt->buffer .= $nbt->endianness === 1 ? \pack("N", $this->value) : \pack("V", $this->value);
	}
}
