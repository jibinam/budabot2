<?php
$atailor_txt = "<header>::::: Guide to A Tailor's Woe  :::::<end>\n\n"; 
$atailor_txt = " 	
This is Edgar. Well. Actually, the OT Tailors have no names, but reliable sources tells us this one is called Edgar. Okay, so fine, we made it up. Anyway. The OT Tailors have been having a problem lately, it seems that they are in dire need of some Rollerrat flesh in order to make the Omni-Tek clothes they sell, and it so happens that they could use your help. Now fancy that, eh?
  The OT Tailors can be found scattered around various OT settlements and cities on Rubi-Ka, and we heartily recommend that you go look for the one located in the middle square of Omni-Trade. Strike up a conversation with him and you'll soon find out that he has a small task for you, involving the killing of a multitude of rats.
                              Mission: Bring back Undamaged Piece of Rubbery Rollerrat Flesh

  Before we actually start doing the mission, there's a few things you should know. First off - the rewards that the tailor will give you depends on your breed. If you are Atrox, you will get Black Suit parts, Nanomages gets Executive Suit parts, Opifexes get Intern-Ops parts and Solitus gets the Grey Suit parts. The parts are not nodrop and have no breed-requirements however, so it is possible to trade them away.
  Each time you get the mission, you will receive a random part. So be wary that if you are gathering enough parts for a whole set. Shift-click the mission icon to find out what the reward is, and if it is a part you already have, you can delete it and talk to the tailor again to get a new one for another part. With that said - its time to get out there and look the enemy dead in the eye!
   Rollerrats can be found pretty much everywhere. Larger groups of rats are hard to find, but just outside of Omni-Entertainment in Omni-Forest you will find some, and there is a lot of rollerrats in Harry's Outpost in Lush Fields as well.
 Rollerrats are aggressive little buggers, so if you are below level 5, meeting them can be an ugly experience, since they hit pretty hard.
Rollerrat's will not always drop the sought-after rollerrat flesh, so you might find yourself hunting them for a while.
 	


Before we actually start doing the mission, there's a few things you should know. First off - the rewards that the tailor will give you depends on your breed. If you are Atrox, you will get Black Suit parts, Nanomages gets Executive Suit parts, Opifexes get Intern-Ops parts and Solitus gets the Grey Suit parts. The parts are not nodrop and have no breed-requirements however, so it is possible to trade them away.

Each time you get the mission, you will receive a random part. So be wary that if you are gathering enough parts for a whole set. Shift-click the mission icon to find out what the reward is, and if it is a part you already have, you can delete it and talk to the tailor again to get a new one for another part. With that said - its not time to get out there and look the enemy dead in the eye!

	  	And this is the face of the enemy! Know it well.

Rollerrats can be found pretty much everywhere. Larger groups of rats are hard to find, but just outside of Omni-Entertainment in Omni-Forest you will find some, and there is a lot of rollerrats in Harry's Outpost in Lush Fields as well.
   Rollerrats are aggressive little buggers, so if you are below level 5, meeting them can be an ugly experience, since they hit pretty hard.
  Rollerrat's will not always drop the sought-after rollerrat flesh, so you might find yourself hunting them for a while.
  You will need 6 pieces of rollerrat flesh for a complete set (the sets have no backslot or helmet armor pieces).
  Once you have all the six pieces you need, it's time to head back to the Tailor and start handing them in. Remember - if you get a mission for a part you already have, simply delete the mission and you can grab another, hopefully for a part you don't already possess. Once you have gotten your nifty new set, simply put it on and you'll be the pride of Omni-Tek!
 The sets reminds a lot about the regular sets you can buy from the Omni-Tek Tailors or indeed from the Clothes Booth in any shop. However, they do differ substantially, since these are the armor variants of the clothes. Lets take a look at the stats shall we.. All suits are complete (ie, includes two sleeves).

Executive Suit  		 
 Chemical AC 75
 Melee AC 75
 Radiation AC 75
 Projectile AC 75
 Energy AC 75
 Cold AC 75
 Fire AC 75
 Poison AC 75
Requirement
 Omni-Tek
Modifiers
 Nanopool 12
 Nano Cost -2%
 Biomet 6
 Matmet 6
 Mat Crea 6
 Time Space 6

Grey Suit-
 Chemical AC 125
 Melee AC 125
 Radiation AC 125
 Projectile AC 125
 Energy AC 125
 Cold AC 125
 Fire AC 125
 Poison AC 125

Requirement
 Omni-Tek

Modifiers
 XP Modifier 1%
 Pistol 12
 Shotgun 12
 Flingshot 12
 Mapnav6
 Complit 6
 Psy Mod 6
 Sense Imp 6

Black Suit-
 Chemical AC 125
 Melee AC 125
 Radiation AC 125
 Projectile AC 125
 Energy AC 125
 Cold AC 125
 Fire AC 125
 Poison AC 125

Requirement
 Omni-Tek

Modifiers
 Body Dev 12
 All Add Def 6
 All Add Off 6
 Brawl 6
 Scale 2

Intern-Ops Suit-
 Chemical AC 75
 Melee AC 75
 Radiation AC 75
 Projectile AC 75
 Energy AC 75
 Cold AC 75
 Fire AC 75
 Poison AC 75

Requirement
 Omni-Tek

Modifiers
 Evade Clsc 24
 Dodge Rng 24
 Duck Exp 24
 Melee init 24
 Physical init 24
 Ranged init 24
 Runspeed 24
 Critical Increase 1%

 As you can clearly see, the suits are definately geared towards the different breeds, although in no way are you forced to use one suit. As mentioned before, they are not nodrop so can be sold or traded. As far as the individual suits goes - the AC's are low, although so are the requirements. It is a nice enough armor to get started in but lack of true protection and upgradeability keeps the judges from holding up all 10's. The life-expentancy of this armor we would say is mostly geared towards level 1-5 people, which is odd since the average rollerrat will steamroll people around that level. Nevertheless, it should hold you until you can get your hands on some real armor. ";

$atailor_txt = Text::makeLink("Guide to A Tailor's Woe", $atailor_txt); 
if($type == "msg") 
$this->send($atailor_txt, $sender); 
elseif($type == "all") 
$this->send($atailor_txt); 
else 
$this->send($atailor_txt, "guild"); 
?>