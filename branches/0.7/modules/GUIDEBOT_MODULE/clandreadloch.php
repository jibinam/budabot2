<?php
$clandreadloch_txt = "<header>::::: Information about Dreadloch Camps :::::<end>\n\n"; 
$clandreadloch_txt ="<font color = yellow>::::: Information about Dreadloch Camps :::::</font>


<font color = #31D6FF>Dreadloch Camps</font>

Omni Camps (Clan Quests)

Guide suited for: All Classes
Faction: Clan
Level Range: 210-220

Captain Cante Hawke needs your help!! Naturally, after the installation of the Battlestions, there's been an increase in the arms race. Omni-Tek wants to control them and the clans want to make sure they don't. 

He informs you that he has received many new reports of high corporate activity related to heavy assault training such as Mechanical vehicles, rocket launchers, special forces, and such. 

The Clans have located 5 key Omni-tek camps. He wants you to lead the force to stop each one. 

Captain Hawk is located near the Tower shop, in Old Athens. 

All these bosses have a chance to drop any random Dreadloch weapons, high quality clumps to improve Ofab weapons and armor as well as the new combat token board. You do not need to mission to kill the boss for this loot. Although, completing the mission will give you a very nice item : a 50 token + 500 VP merit token.  The Bosses seem to have a two hour spawn time.


<font color = blue>Confirmed Loot Table: </font>
<font color = blue>________________________________</font>
Smuggled Nanite Merit Board Base 
Smuggled Combat Merit Board Base 
Dreadloch Obliterator 
Dreadloch Endurance Booster 
Dreadloch Combat Remodulator 
Dreadloch Sniper's Friend 
Dreadloch Stabilising Aid 
Dreadloch Shen Sticks 
Dreadloch Thrasher 
Dreadloch Rapier 
Dreadloch Remodulator 
Dreadloch Survival Predictor 
Dreadloch Balanced Freedom Arms 
Dreadloch Aiming Apparatus
<font color = blue>________________________________</font> 


<font color = yellow>Clondyke </font>

Captain Hawk's resourses tell him that there's a certain commander in Clondyke. He was dishonorably discharged from OTAF, and recruited by the Unicorrns. He is known simply as Force Recon Commander 191 and he now tests pilot prototype mechs. Your job is to get rid of him. 

Force Recon Commander 191 is in western Clondyke. 

Force Recon Commander 191 is in the base, his movements are shown above

You will first have to kill 5 Deaths Squads that are around his camp. These shouldn't be too hard to get rid of. 

When you have killed all 5, it is time to take on the Force Recon Commander 191. He walks around the camp. Wait until he is close to the entrance to pull him out. It may be best to clear a few of the adds around him, but watch it, he moves fast. He is in a mech, and you will have to mech up to kill him. 


<font color = yellow>Eastern Foul Plains</font> 

The have found an Alien artifact, some kind of signal tower. It will be your job to destroy it, and kill the commander in charge of it : Special Agent Moxy. You will be given a special tool to destroy the tower. 

The Signal tower is located in the northern part of Eastern Foul Plains. 

Your first task will be to clear the area of guards. Kill 5 Perimeter Enforcements. Beware of the scientist, they have healing capabilities. 

Once these are disposed of, you will have to target the tower, and use the tool that Captain Hawk gave you. This tool will send a signal to an orbital laser, so once you use it, run like crasy. Once it is blown up, Special Agent Moxy will appear. 

She is not stationary and will follow you. She is quite dangerous as she hits really hard, and she has the ability to completely wipe your ncu. 


<font color = yellow>Pleasant Meadows </font>

Major Woon has restored an abandoned Outpost in Pleasant Meadows. It is suspected that he is training troups here. Captain Hawk wants you to stop them. 

The camp location is in south-west PM : 

You will first have to exterminate 5 Assault Troopers. These are found all around the camp, and inside also. It might be a good idea to kill Corporal Morrisson and Sergeant Kingdon, as they might give you problems later on. 

When you have completed the 5 kills. You will then have to kill Major Woon, who is located inside the camp. He has a massive HP bar and is quite strong. He is stationary however, so he will not follow you around. However, he will warp people to him, which will be problematic. He also has an AoE stun. It is a good idea that you kill everyone around the camp before you confront him, as if there are some guards left around, they will come and help their commander. The docs should stay well away of his stun, as stunned docs can lead to disaster. 


<font color = yellow>Southern Artery Valley</font> 

There's a new camp in SAV that specializes in the training of assault vehicle drivers, gunners and loaders. You are ordered to destroy this camp, and the person who runs it : Assault Commander Pax. 

The base is located in the southern part of Southern Artery Valley. 

Once here, you will first have to exterminate 5 OT special forces. These should not pose too much of a problem. They are pretty spaced out, so pulling should be easy as well. 

Once this is done, you will have to kill Assault Commander Pax, who is located in the middle of the camp. 

She is also stationary, so this fight will be easier if you kill all the adds around her. She spawns adds, named Executive Defenders. These shouldn't pose problems. However, when she gets just past the halfway mark, she spawns 2 huge mechs Level 250 (Assault Sargeant Helene Moneal and Assault Sargeant Ellen Moneal). These are very nasty, and they seem very drawn to heal aggro. 


<font color = yellow>Upper Street West Bank </font> 

One of the Thunder Brothers, Peal Thunder, has lost a few marbles. Captain Hawk would like you to exterminate him before he launches some missile attack on civilian targets. He gives you a warning that he runs an Elite group of agents. 

<font color = red>BEWARE!!</font> This zone is 25% and you will be open for pvp. Many guards inside this encampment will also flag you for pvp. 

His base is in the western part of Upper Street West Bank : 

You will have to dispose of 5 Stealth Troopers. These are agents, so they are hidden. If you do not have enough perception, you will just have to carefully walk around until one decides to gank you. At this point, pull him to a safe spot to kill him. 

When you succesfully kill 5, you will have to get rid of Peal Thunder. He is inside the encampment, hiding behind a desk with a rocket launcher. He will not move from that spot. 



<font color = blue>Mission Reward </font>
Military Reward Insignia -- 50 tokens and some VP 

<font color = yellow>_______________________________________________________</font>

Last updated on 03.19.2007 by Windkeeper
Written by Tepamina. Additonal information provided by Windkeeper.ddreadloch";

 
$clandreadloch_txt = Text::makeLink("Clan Dreadloch Camps", $clandreadloch_txt); 
if($type == "msg") 
$chatBot->send($clandreadloch_txt, $sender); 
elseif($type == "priv") 
$chatBot->send($clandreadloch_text); 
else 
$chatBot->send($clandreadloch_txt, "guild");
?>