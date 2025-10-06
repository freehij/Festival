<?php


namespace pocketmine\nbt\tag;

use pocketmine\nbt\NBT;

class StringTag extends NamedTag
{

	public function getType()
	{
		return NBT::TAG_String;
	}

	public function read(NBT $nbt)
	{
		$this->value = $nbt->get($nbt->endianness === 1 ? \unpack("n", $nbt->get(2))[1] : \unpack("v", $nbt->get(2))[1]);
	}

	public function write(NBT $nbt)
	{
		$nbt->buffer .= $nbt->endianness === 1 ? \pack("n", \strlen($this->value)) : \pack("v", \strlen($this->value));
		$nbt->buffer .= $this->value;
	}
}
