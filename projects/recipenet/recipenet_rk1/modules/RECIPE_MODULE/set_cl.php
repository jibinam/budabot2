<?
   /*
   ** Author: Captainzero (RK1)
   ** Description: Set CL
   ** Version: 0.1
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 11/11/2008
   ** Date(last modified): 11/11/2008
   ** 
   */

$cl_value = eregi_replace("^cl ","", $message);

if (is_numeric($cl_value)) {

		$db->query("SELECT * FROM members_rk1 WHERE member_name = '$sender'");
		$exists = $db->numrows();

		if ($exists == 0) {
			$db->query("INSERT INTO members_rk1 (member_name, member_cl) VALUES ('$sender', '$cl_value')");
		} else {
			$db->query("UPDATE members_rk1 SET member_name='$sender', member_cl='$cl_value' WHERE member_name = '$sender'");
		}
		$msg = "Thank you ".$sender.". your CL has been set to ".$cl_value;
} else {
		$msg = "I cant do that!  Try using a number ...";
}
        bot::send($msg, $sender);

?>