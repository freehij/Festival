<?php



namespace pocketmine\utils;

use pocketmine\Thread;

class ServerKiller extends Thread{

	public $time;

	public function __construct($time = 15){
		$this->time = $time;
	}

	public function run(){
		\sleep($this->time);
		echo "\nForce killed PMMP!\n";
		@\pocketmine\kill(\getmypid());
	}

	public function getThreadName(){
		return "Server Killer";
	}
}