<? 
$elykeyclan_txt = "<header>::::: Elysium Garden Key Quest CLAN :::::<end>\n\n"; 
$elykeyclan_txt = "
<font color='#ff9933'><highlight>Enel Garden Key Quest</font></end>

<highlight>Guide suited for:</end> All Classes
<highlight>Faction:</end> Clan
<highlight>Level Range:</end> 50-100

There are 3 Redeemed Temples in Elysium. 

One in the center of the map, 1 North East, and another, slightly less obvious, South West. 

Step 1: 
First off head to the Central Temple and inside. Up some spiral stairs and take the entrance on the North. 
Speak to Ecclesiast Enel Gill and get sealed letter. 
Then show it to all of the following NPC's in order. 
The missions tells you who to see next (there is also another Enel Gil outside the Temple you can talk to). 

Chat with him, ask about the journey, and you'll get sealed letter. 
You'll get a new mission. 

The mission gives you the name of the NPC's to see next. You must follow the order that is given to you at each step. 

<highlight>Locations for NPC's: </end>

Central Temple - Inside 
1. Devoted Enel Thar-Ilad 
2. Diviner Ene Wei-Nuir 
3. Sipius Enel Mara-Ilad 

South West Temple - Outside 
4. Devoted Enel Cama-Lux 
5. Diviner Enel Thar-Thar 

North East Temple - In a 'hut' 
6. Watcher Enel Ulma-Thar 

489 x 1393 : Archbile 
7. Acolyte Enel Wei 

Step 2: 
Head back to Ecclesiast Enel Gill in the central temple. He gives you the letter back and instructs you to go Enel's Garden. Speak to Forrester Enel Aban and he gives you an Ancient Tagging Device. 

Step 3: 
You then need to tag all the Un-reedemed mobs in the following order: ( all found at un-reedemend temples. 

1588 721 : Remnans 
1. Fortuitous Jorr-Fes Shere 
2. Hypnagogic Wox-Xum Shere 
3. Follower Chi-Nar Shere 

737 566 : Stormshelter 
4. Fortuitous Hes-Man Shere 
5.Hypnagogic Ixu-Bhotaar Shere 
6. Follower Yutt-Ixi Share 

899 418 : Nero 
7. Follower Pi-Zul Shere 
8. Man-Wox Shere 

Step 5: 
Head back to Forrester Enel Aban and give him the Ancient Tagging Device back, and voila! the key. 

--------------------------------------------------------------------------------
Last updated on 10.08.2006 by Trgeorge
Information originally provided by Roedran and Herodotus to the SL Library Forums and at the Official AO Forums. Additional information provided by Windguaerd.
Courtesy of AO Universe
"
;
$elykeyclan_txt = $this->makeLink("Elysium: Garden Key Quest Clan", $elykeyclan_txt); 
if($type == "msg") 
$this->send($elykeyclan_txt, $sender); 
elseif($type == "all") 
$this->send($elykeyclan_txt); 
else 
$this->send($elykeyclan_txt, "guild"); 
?>