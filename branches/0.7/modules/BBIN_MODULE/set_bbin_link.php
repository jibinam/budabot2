<?php
	 /*
   ** Author: Mindrila (RK1)
   ** Credits: Legendadv (RK2)
   ** BUDABOT IRC NETWORK MODULE
   ** Version = 0.1
   ** Developed for: Budabot(http://budabot.com)
   **
   */
   
Settings::save("bbin_status", 0);
if (Settings::get('bbin_autoconnect') == 1) {
	include 'bbin_connect.php';
}
?>
