<?php

   /*
   ** Author: Captainzero (RK1)
   ** Description: Recipe module
   ** Version: 0.1
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 11/11/2008
   ** Date(last modified): 11/11/2008
   ** 
   */


$MODULE_NAME = "RECIPE_MODULE";
$PLUGIN_VERSION = 0.1;

	bot::command("msg", "$MODULE_NAME/recipenet.php", "<a", "all", "Identify Recipes for an Item");
	bot::command("msg", "$MODULE_NAME/recipeshow.php", "rshow", "all", "Show me a recipe");
	bot::command("msg", "$MODULE_NAME/itemshow.php", "ishow", "all", "Show me an item");
	bot::command("msg", "$MODULE_NAME/typeshow.php", "tshow", "all", "Display a recipe type");
	bot::command("msg", "$MODULE_NAME/swshow.php", "swshow", "all", "Display a recipe type");
	bot::command("msg", "$MODULE_NAME/menu.php", "recipe", "all", "Main Menu");
	bot::command("msg", "$MODULE_NAME/search.php", "search", "all", "Search for a recipe");
	bot::command("msg", "$MODULE_NAME/report.php", "report", "all", "Make a report");
	bot::command("msg", "$MODULE_NAME/set_cl.php", "cl", "all", "Set your CL value");
    bot::help("rec", "$MODULE_NAME/recipe.txt", "all", "Recipe Help", "Recipe Module"); 
	
?>
