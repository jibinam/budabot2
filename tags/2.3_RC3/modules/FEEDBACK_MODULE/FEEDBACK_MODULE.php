<?php
	DB::loadSQLFile($MODULE_NAME, "feedback");
    
	Command::register($MODULE_NAME, "", "feedback.php", "feedback", "all", "Allows people to add and see feedback");
	
	Help::register($MODULE_NAME, "feedback", "feedback.txt", "all", "Feedback usage");
?>