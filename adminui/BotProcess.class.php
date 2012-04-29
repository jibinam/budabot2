<?php

// register the class into GTK to enable custom signals
GObject::register_type('BotProcess');

/**
 * The BotProcess class executes new Budabot processes. 
 */
class BotProcess extends GObject {
	
	private $processResource;
	private $outFile;
	private $errorFile;
	private $timerIds;
	private $started;
	
	/**
	 * Define custom signals that this class can emit.
	 */
	public $__gsignals = array(
		// emitted when the process has finished executing
		'stopped'         => array(GObject::SIGNAL_RUN_LAST, GObject::TYPE_NONE, array()),
		// emitted when the process sends data to standard output
		'stdout_received' => array(GObject::SIGNAL_RUN_LAST, GObject::TYPE_NONE, array(GObject::TYPE_STRING)),
		// emitted when the process sends data to standard error
		'stderr_received' => array(GObject::SIGNAL_RUN_LAST, GObject::TYPE_NONE, array(GObject::TYPE_STRING))
	);
	
	/**
	 * Class constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->reset();
	}
	
	/**
	 * Calling this method will start the bot as its own process.
	 * When the bot is running its stdout and stderr is emitted
	 * with 'stdout_received' and 'stderr_received' signals.
	 *
	 * Call stop() to terminate the process.
	 */
	public function start() {
		// do nothing if bot is already running
		if ($this->started) {
			return;
		}
		
		$this->reset();

		// If the bot is running under Windows, use php.exe 
		// and the Windows-specific php-win.ini, else use 
		// PHP and the system default php.ini, if any, or a 
		// local custom php.ini if it exists (hence the new 
		// name for the Windows-specific ini-file). 
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') { 
			$php_exec = "win32\\php.exe -c php-win.ini"; 
		} else { 
			$php_exec = "php"; 
		}
		
		$php_file = "main.php -- ./conf/config.php"; //"adminui/loop_test.php";
		
		// create temp files to hard disk for STDOUT and STDERR
		$this->outFile   = $this->createTempFile();
		$this->errorFile = $this->createTempFile();
		
		// start the process
		$this->processResource = proc_open(
			"$php_exec -f $php_file",
			array(
				1 => $this->outFile->handle,  // stdout
				2 => $this->errorFile->handle // stderr
			),
			$pipes,
			dirname(__FILE__) . '/..',
			null, // use same env environment as current script
			array( 'bypass_shell' => true )
		);
		if (is_resource($this->processResource)) {
			// poll bot's outputs every 100ms
			$this->timerIds[] = Gtk::timeout_add(100, array($this, 'readStdout'));
			$this->timerIds[] = Gtk::timeout_add(100, array($this, 'readStderr'));
			$this->timerIds[] = Gtk::timeout_add(100, array($this, 'checkIfDead'));
			
			$this->started = true;
		}
	}
	
	/**
	 * Calling this method terminates the running bot process.
	 * Emits 'stopped' when finished.
	 */
	public function stop() {
		if ($this->started) {
			$this->reset();
			// notify listeners
			$this->emit('stopped');
		}
	}
	
	/**
	 * Reads data from process's STDOUT and emits the data through
	 * 'stdout_received' signal.
	 * This method is called automatically by a timer when process
	 * is running.
	 */
	public function readStdout() {
		$contents = $this->readNewContent($this->outFile);
		if ($contents) {
			$this->emit('stdout_received', $contents);
		}
		return true;
	}

	/**
	 * Reads data from process's STDERR and emits the data through
	 * 'stderr_received' signal.
	 * This method is called automatically by a timer when process
	 * is running.
	 */
	public function readStderr() {
		$contents = $this->readNewContent($this->errorFile);
		if ($contents) {
			$this->emit('stderr_received', $contents);
		}
		return true;
	}
	
	/**
	 * Checks if the process is currently running or not, calls stop() if not.
	 * This method is called automatically by a timer when process is running.
	 */
	public function checkIfDead() {
		$status = proc_get_status($this->processResource);
		if ($status) {
			if ($status['running'] == false) {
				$this->stop();
			}
		}
		return true;
	}
	
	/**
	 * Reads any new content from given @a file and returns it to caller.
	 */
	private function readNewContent($file) {
		$contents = '';
		clearstatcache();
		$currentSize = filesize($file->path);
		if ($file->previousSize != $currentSize) {
			$fileHandle = fopen($file->path, 'r');
			fseek($fileHandle, $file->previousSize);
			$contents = fread($fileHandle, $currentSize - $file->previousSize);
			fclose($fileHandle);
			$file->previousSize = $currentSize;
		}
		return $contents;
	}
	
	/**
	 * Creates a temp file and returns its info back to caller.
	 */
	private function createTempFile() {
		// create a temp file
		$fileHandle = tmpfile();
		
		// get path of the temp file
		$meta_data = stream_get_meta_data($fileHandle);
		$filename = $meta_data["uri"];
		
		// build a container object for the file and return it
		$out = new StdClass();
		$out->handle = $fileHandle;
		$out->path   = $meta_data["uri"];
		$out->previousSize = 0;
		return $out;
	}
	
	/**
	 * Stops any polling timers, closes handles and terminates the running
	 * process if any and resets values back to default.
	 */
	private function reset() {
		// stop timers
		if (is_array($this->timerIds)) {
			foreach ($this->timerIds as $timerId) {
				Gtk::timeout_remove($timerId);
			}
		}
		// close handles
		@fclose($this->outFile);
		@fclose($this->errorFile);
		@proc_terminate($this->processResource);
		@proc_close($this->processResource);
		
		// reset values
		$this->processResource = 0;
		$this->outFile = 0;
		$this->errorFile = 0;
		$this->timerIds = array();
		$this->started = false;
	}
}
