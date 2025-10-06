<?php



namespace pocketmine\event\inventory;

use pocketmine\event\Cancellable;
use pocketmine\event\Event;
use pocketmine\inventory\CraftingTransactionGroup;
use pocketmine\inventory\Recipe;

class CraftItemEvent extends Event implements Cancellable{
	public static $handlerList = \null;

	/** @var CraftingTransactionGroup */
	private $ts;
	/** @var Recipe */
	private $recipe;

	/**
	 * @param CraftingTransactionGroup $ts
	 * @param Recipe                   $recipe
	 */
	public function __construct(CraftingTransactionGroup $ts, Recipe $recipe){
		$this->ts = $ts;
		$this->recipe = $recipe;
	}

	/**
	 * @return CraftingTransactionGroup
	 */
	public function getTransaction(){
		return $this->ts;
	}

	/**
	 * @return Recipe
	 */
	public function getRecipe(){
		return $this->recipe;
	}

}