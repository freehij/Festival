<?php



namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class GarbageCollectorCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%pocketmine.command.gc.description",
			"%pocketmine.command.gc.usage"
		);
		$this->setPermission("pocketmine.command.gc");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return \true;
		}

		$chunksCollected = 0;
		$entitiesCollected = 0;
		$tilesCollected = 0;

		$memory = \memory_get_usage();

		foreach($sender->getServer()->getLevels() as $level){
			$diff = [\count($level->getChunks()), \count($level->getEntities()), \count($level->getTiles())];
			$level->doChunkGarbageCollection();
			$level->unloadChunks(\true);
			$chunksCollected += $diff[0] - \count($level->getChunks());
			$entitiesCollected += $diff[1] - \count($level->getEntities());
			$tilesCollected += $diff[2] - \count($level->getTiles());
			$level->clearCache(\true);
		}

		$cyclesCollected = $sender->getServer()->getMemoryManager()->triggerGarbageCollector();

		$sender->sendMessage(TextFormat::GREEN . "---- " . TextFormat::WHITE . "Garbage collection result" . TextFormat::GREEN . " ----");
		$sender->sendMessage(TextFormat::GOLD . "Chunks: " . TextFormat::RED . \number_format($chunksCollected));
		$sender->sendMessage(TextFormat::GOLD . "Entities: " . TextFormat::RED . \number_format($entitiesCollected));
		$sender->sendMessage(TextFormat::GOLD . "Tiles: " . TextFormat::RED . \number_format($tilesCollected));

		$sender->sendMessage(TextFormat::GOLD . "Cycles: " . TextFormat::RED . \number_format($cyclesCollected));
		$sender->sendMessage(TextFormat::GOLD . "Memory freed: " . TextFormat::RED . \number_format(\round((($memory - \memory_get_usage()) / 1024) / 1024, 2))." MB");
		return \true;
	}
}
