<?php


namespace pocketmine\nbt\tag;

use pocketmine\nbt\NBT;

class ByteTag extends NamedTag
{

	public function getType()
	{
		return NBT::TAG_Byte;
	}

	public function read(NBT $nbt)
	{
		$this->value = \ord($nbt->get(1));
	}

	public function write(NBT $nbt)
	{
		$nbt->buffer .= \chr($this->value);
	}
}
