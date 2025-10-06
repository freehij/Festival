<?php


namespace pocketmine\nbt\tag;

use pocketmine\nbt\NBT;
use pocketmine\utils\Binary;

class LongTag extends NamedTag
{

	public function getType()
	{
		return NBT::TAG_Long;
	}

	public function read(NBT $nbt)
	{
		$this->value = $nbt->endianness === 1 ? Binary::readLong($nbt->get(8)) : Binary::readLLong($nbt->get(8));
	}

	public function write(NBT $nbt)
	{
		$nbt->buffer .= $nbt->endianness === 1 ? Binary::writeLong($this->value) : Binary::writeLLong($this->value);
	}
}
