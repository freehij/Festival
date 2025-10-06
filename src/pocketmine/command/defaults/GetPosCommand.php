<?php



namespace pocketmine\command\defaults;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\TranslationContainer;
use pocketmine\Player;

class GetPosCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"Get the player position",
			"%commands.generic.usage",
			["/getpos"]
		);
		$this->setPermission("festival.command.getpos");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender) || !($sender instanceof Player)){
			return \true;
		}
		$x = round($sender->x, 2);
		$y = round($sender->y, 2);
		$z = round($sender->z, 2);
		$sender->sendMessage("Position: §9X: $x Y: $y Z: $z");
		return \true;
	}
}