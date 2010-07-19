<?php
   /*
   ** Author: Legendadv (RK2)
   ** IRC RELAY MODULE
   **
   ** Developed for: Budabot(http://aodevs.com/index.php/topic,512.0.html)
   **
   */
   
Settings::save("irc_status", 0);
if (Settings::get('irc_autoconnect') == 1) {
	include 'irc_connect.php';
}
?>
