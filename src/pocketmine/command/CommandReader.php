<?php



namespace pocketmine\command;

use pocketmine\Thread;

class CommandReader extends Thread{
	private $readline;
	/** @var \Threaded */
	protected $buffer;
	private $shutdown = false;

	public function __construct(){
		$this->buffer = new \Threaded;
		$this->start();
	}

	public function kill(){
		$this->shutdown = true;
	}

	private function readLine(){
		if(!$this->readline){
			global $stdin;

			if(!is_resource($stdin)){
				return "";
			}

			return trim(fgets($stdin));
		}else{
			$line = trim(readline("> "));
			if($line != ""){
				readline_add_history($line);
			}

			return $line;
		}
	}
	public function quit(){
		//https://github.com/krakjoe/pthreads/issues/708 -> Thread::kill
		// > The method was removed because it was not safe to perform.
		// Thanks krakjoe, now i cant force stop the thread (i hate you why did u remove it)
		
		
		//Do nothing
	}
	/**
	 * Reads a line from console, if available. Returns null if not available
	 *
	 * @return string|null
	 */
	public function getLine(){
		if($this->buffer->count() !== 0){
			return $this->buffer->shift();
		}

		return null;
	}

	public function run(){
		$opts = getopt("", ["disable-readline"]);
		if(extension_loaded("readline") and !isset($opts["disable-readline"])){
			$this->readline = true;
		}else{
			global $stdin;
			$stdin = fopen("php://stdin", "r");
			stream_set_blocking($stdin, 0);
			$this->readline = false;
		}

		$lastLine = microtime(true);
		while(!$this->shutdown){
			if(($line = $this->readLine()) !== ""){
				$this->buffer[] = preg_replace("#\\x1b\\x5b([^\\x1b]*\\x7e|[\\x40-\\x50])#", "", $line);
			}elseif(!$this->shutdown and (microtime(true) - $lastLine) <= 0.1){ //Non blocking! Sleep to save CPU
				$this->synchronized(function(){
					$this->wait(10000);
				});
			}

			$lastLine = microtime(true);
		}
	}

	public function getThreadName(){
		return "Console";
	}
}