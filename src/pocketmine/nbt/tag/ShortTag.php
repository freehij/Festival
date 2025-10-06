<?php


namespace pocketmine\nbt\tag;

use pocketmine\nbt\NBT;

class ShortTag extends NamedTag
{

	public function getType()
	{
		return NBT::TAG_Short;
	}

	public function read(NBT $nbt)
	{
		$this->value = $nbt->endianness === 1 ? \unpack("n", $nbt->get(2))[1] : \unpack("v", $nbt->get(2))[1];
	}

	public function write(NBT $nbt)
	{
		$nbt->buffer .= $nbt->endianness === 1 ? \pack("n", $this->value) : \pack("v", $this->value);
	}
}
