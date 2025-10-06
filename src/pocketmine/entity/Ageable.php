<?php


namespace pocketmine\entity;


interface Ageable{
	const DATA_AGEABLE = 14;
	
	const DATA_FLAG_NOTBABY = 0;
	const DATA_FLAG_BABY = 1;

	public function isBaby();
}