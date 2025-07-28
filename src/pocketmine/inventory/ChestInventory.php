<?php



namespace pocketmine\inventory;

use pocketmine\level\Level;
use pocketmine\network\Network;
use pocketmine\network\protocol\TileEventPacket;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\tile\Chest;

class ChestInventory extends ContainerInventory{
	public function __construct(Chest $tile){
		parent::__construct($tile, InventoryType::get(InventoryType::CHEST));
	}

	/**
	 * @return Chest
	 */
	public function getHolder(){
		return $this->holder;
	}

	public function onOpen(Player $who){
		parent::onOpen($who);

		if(\count($this->getViewers()) === 1){
			$pk = new TileEventPacket();
			$pk->x = $this->getHolder()->getX();
			$pk->y = $this->getHolder()->getY();
			$pk->z = $this->getHolder()->getZ();
			$pk->case1 = 1;
			$pk->case2 = 2;
			if(($level = $this->getHolder()->getLevel()) instanceof Level){
				Server::broadcastPacket($level->getChunkPlayers($this->getHolder()->getX() >> 4, $this->getHolder()->getZ() >> 4), $pk->setChannel(Network::CHANNEL_WORLD_EVENTS));
			}
		}
	}

	public function onClose(Player $who){
		if(\count($this->getViewers()) === 1){
			$pk = new TileEventPacket();
			$pk->x = $this->getHolder()->getX();
			$pk->y = $this->getHolder()->getY();
			$pk->z = $this->getHolder()->getZ();
			$pk->case1 = 1;
			$pk->case2 = 0;
			if(($level = $this->getHolder()->getLevel()) instanceof Level){
				Server::broadcastPacket($level->getChunkPlayers($this->getHolder()->getX() >> 4, $this->getHolder()->getZ() >> 4), $pk->setChannel(Network::CHANNEL_WORLD_EVENTS));
			}
		}
		parent::onClose($who);
	}
}