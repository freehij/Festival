<?php



namespace pocketmine\inventory;

interface Recipe{

	/**
	 * @return \pocketmine\item\Item
	 */
	public function getResult();

	public function registerToCraftingManager();
}