Hi all, thx for your interesst in recipebot
for the database :

table explanation :
dedicace : Is use to set a personnal sentence on recipebot for special user. I use it in reward for help, repport or so
EstUtiliserDans : use to attribute a recipe to an object
filename : used when I siwch from offline recipe to new bot version. I think it's not use aznymore, I can't remember :p
link : used to set a weblink to a recipe (NumLink is in auto increase mode )
memberrk1 & 2 : used to store recipebot's user
	TypeMbr is 0 for usual user
	Cl is the Computer literacy value off the member
	Pub is set to 1 when the user have seen the advertisment (see below)
objets: is a little part off the AO dB ( not up to date ) it's use to tell where they can be found and also to evaluate the selling price
rapport: in this table there is all the repport that player sent me
	estCorrige = 1 mean that I have fixed the error in report
	( so you have here lot's off repport to correct ^^
recette: the heart of recipebot, the huge work : all the data, the recipes.
texte : all the little texte in recipebot link header, footer of each pages, ...
type: title of recipe group.

to run the bot you have to use a php/mysql system (such as easyphp)
for RK you have to run 
recipebotRK1.bat or RK2.bat
the source code of the bot is in recipebot.php
you have to set recipebotparamrk1.php or RK2

I hope it will help you
Bye Bye
Beaexn.

