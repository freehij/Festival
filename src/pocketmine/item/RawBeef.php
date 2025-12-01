<?php
namespace pocketmine\item;


class RawBeef extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::RAW_BEEF, 0, $count, "Raw beef");
	}
}
