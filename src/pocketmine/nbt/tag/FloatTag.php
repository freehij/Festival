<?php


namespace pocketmine\nbt\tag;

use pocketmine\nbt\NBT;

class FloatTag extends NamedTag
{

	public function getType()
	{
		return NBT::TAG_Float;
	}

	public function read(NBT $nbt)
	{
		$this->value = $nbt->endianness === 1 ? (\ENDIANNESS === 0 ? \unpack("f", $nbt->get(4))[1] : \unpack("f", \strrev($nbt->get(4)))[1]) : (\ENDIANNESS === 0 ? \unpack("f", \strrev($nbt->get(4)))[1] : \unpack("f", $nbt->get(4))[1]);
	}

	public function write(NBT $nbt)
	{
		$nbt->buffer .= $nbt->endianness === 1 ? (\ENDIANNESS === 0 ? \pack("f", $this->value) : \strrev(\pack("f", $this->value))) : (\ENDIANNESS === 0 ? \strrev(\pack("f", $this->value)) : \pack("f", $this->value));
	}
}
