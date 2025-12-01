<?php
namespace pocketmine\item;


class Cookie extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::COOKIE, 0, $count, "Cookie");
	}
}
