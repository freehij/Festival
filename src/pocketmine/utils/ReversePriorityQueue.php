<?php



namespace pocketmine\utils;

class ReversePriorityQueue extends \SplPriorityQueue{

	public function compare(mixed $priority1, mixed $priority2): int{
		return (int) -($priority1 - $priority2);
	}
}