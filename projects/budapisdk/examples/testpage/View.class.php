<?php

/**
 * This class acts as a view for testpage example project.
 */
class View {

	/**
	 * Constructor method.
	 * @param $model model object
	 */
	public function __construct($model) {
		$this->model = $model;
		$this->message = '';
	}
	
	/**
	 * This method renders HTML content of the page and returns it to caller.
	 */
	public function render() { 
		// define short hand names for needed variables
		$loggedIn = $this->model->isLoggedIn();
		$message = $this->getMessage($this->message);
		$command = $this->model->command;
		$username = $this->model->username;
		$password = $this->model->password;
		$server = $this->model->server;
		$port = $this->model->port;
		ob_start();
		// ############## HTML template starts ################
		?>
		<html>
		<body>

		<?php echo $message; ?>

		<?php if ($loggedIn): ?>

		<hr />
		<form action="?" method="post">
			Command: <input type="text" name="command" size="50" value="<?php echo $command ?>" /><br />
			<input type="submit" name="action" value="Send" />
		</form>
		<hr />
		<form action="?" method="post">
			<input type="submit" name="action" value="Logout" />
		</form>

		<?php else: ?>

		<form action="?" method="post">
			Username: <input type="text" name="username" value="<?php echo $username ?>" /><br />
			Password: <input type="password" name="password" value="<?php echo $password ?>" /><br />
			Server: <input type="text" name="server" value="<?php echo $server ?>" /><br />
			Port: <input type="text" name="port" value="<?php echo $port ?>" /><br />
			<input type="submit" name="action" value="Connect" />
		</form>

		<?php endIf; ?>

		</body>
		</html>
		<?php
		// ############## HTML template ends ################
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}
	
	/**
	 * Converts given AOML message to HTML.
	 * @param $message AOML message
	 * @return HTML message
	 */
	private function getMessage($message) {
		$message = preg_replace("/<a(\\s+)href=\"text:\/\/(.+)\">(.+)<\/a>/sU", "\\2", $message);
		$message = preg_replace("/<img src=(.+?)>/U", "", $message);
		$message = preg_replace("/<font(.+)>/U", "", $message);
		$message = preg_replace("/<\/font>/U", "", $message);
		$message = str_replace("\n\n\n\n", "<br />", $message);
		$message = str_replace("\n\n\n", "<br />", $message);
		$message = str_replace("\n", "<br />", $message);
		$message = $message . "<br /><br />";
		return $message;
	}
}