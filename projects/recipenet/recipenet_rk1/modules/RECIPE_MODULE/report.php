<?
   /*
   ** Author: Captainzero (RK1)
   ** Description: Report Feature
   ** Version: 0.1
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 11/11/2008
   ** Date(last modified): 11/11/2008
   ** 
   */

		$report_msg = eregi_replace("^report ","", $message);
		$report_sender = $sender;
		$rk = "rk1";
		
		$report = "[".$report_sender."][".date("d/m/Y")."][".$rk."][".$report_msg."]";

		$db->query("INSERT INTO reports (report_text) VALUES ('$report')");
		
		$msg="Thank you for your interest in RecipeNET. Please direct any problems or recipe suggestions to our website: http://aorecipenet.com";

        bot::send($msg, $sender);

?>