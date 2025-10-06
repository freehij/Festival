<?php


namespace pocketmine\nbt\tag;

abstract class NamedTag extends Tag
{

	protected $name;

	/**
	 *
	 * @param string $name
	 * @param bool|float|double|int|byte|short|array|Compound|Enum|string $value
	 */
	public function __construct($name = "", $value = \null)
	{
		$this->name = ($name === \null or $name === \false) ? "" : $name;
		if ($value !== \null) {
			$this->value = $value;
		}
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
	}
}
