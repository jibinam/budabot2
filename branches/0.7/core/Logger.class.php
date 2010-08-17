<?php

global $vars;

// make sure logging directory exists
@mkdir("./logs/{$vars['name']}.{$vars['dimension']}", 0777, true);

// logging levels
define('TRACE', 0);
define('DEBUG', 1);
define('INFO', 2);
define('WARN', 3);
define('ERROR', 4);
define('FATAL', 5);

class Logger {
	public static function log($file, $message, $log_level) {
		$file = array_pop(explode("\\", $file));
		$timestamp = date("Ymd H:i");
		$log_level_description = Logger::get_log_level_description($log_level);
		
		$line = "[$timestamp]\t[$log_level_description]\t[$file]\t$message";

		if ($log_level >= $vars['console_log_level']) {
			echo "$line\n";
		}
		if ($log_level >= $vars['file_log_level']) {
			Logger::append_to_log_file($log_level_description, $line);
		}
		
		if ($log_level >= WARN) {
			die('The bot is shutting down.');
		}
		
		/*
			[00:00] [DEBUG] [/modules/TOWER_MODULE/towers.php] [timer check]
			[00:00] [INFO] [/modules/TOWER_MODULE/towers.php] [tower site added]
			[00:00] [WARN] [/modules/TOWER_MODULE/towers.php] [could not connect to twinknet]
			[00:00] [ERROR] [/modules/TOWER_MODULE/towers.php] [sql error]
			
			201008.debug.txt
			201008.info.txt
			201008.warn.txt
			201008.error.txt
		*/
	}
	
/*===============================
** Name: log
** Record incoming info into the chatbot's log.
*/	public static function log_chat($channel, $sender, $message) {
		$log_level = INFO;
		$timestamp = date("Ymd H:i");
		
		$message = preg_replace("/<font(.+)>/U", "", $message);
        $message = preg_replace("/<\/font>/U", "", $message);
        $message = preg_replace("/<a(\\s+)href=\"(.+)\">/sU", "[link]", $message);
        $message = preg_replace("/<a(\\s+)href='(.+)'>/sU", "[link]", $message);
        $message = preg_replace("/<\/a>/U", "[/link]", $message);

		if ($channel == "Buddy") {
			$line = "[$timestamp] [$channel] $sender $message";
		} else if ($sender == -1) {
			$line = "[$timestamp] [$channel] $message";
		} else {
			$line = "[$timestamp] [$channel] $sender: $message";
		}

		if ($channel == "Inc. Msg." || $channel == "Out. Msg.") {
			$channel = "Tells";
		}

		if ($log_level >= $vars['console_log_level']) {
			echo "$line\n";
		}
		if ($log_level >= $vars['file_log_level']) {
			Logger::append_to_log_file($channel, $line);
		}
	}

	public static function get_log_level_description($log_level) {
		switch ($log_level) {
			case TRACE:
				return "TRACE";
			case DEBUG:
				return "DEBUG";
			case INFO:
				return "INFO";
			case WARN:
				return "WARN";
			case ERROR:
				return "ERROR";
			case FATAL:
				return "FATAL";
			default:
				// TODO log this error?
				return "UNKNOWN";
		}
	}

	private static function append_to_log_file($channel, $line) {
		global $vars;

		$today =  date("Ym");

		// Open and append to log-file. Complain on failure.
        $filename = "./logs/{$vars['name']}.{$vars['dimension']}/$today.$channel.log";
        if (($fp = fopen($filename, "a")) === FALSE) {
            echo "    *** Failed to open log-file $filename for writing ***\n";
        } else {
            fwrite($fp, $line . PHP_EOL);
            fclose($fp);
        }
	}
}

?>
