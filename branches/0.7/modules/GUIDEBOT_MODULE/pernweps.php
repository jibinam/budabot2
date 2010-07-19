<? 
$pernweps_txt = "<header>::::: Perennium Weapons :::::<end>\n\n"; 
$pernweps_txt = "Perennium Weapons

Guide suited for: Agent, Fixer, Soldier
Faction: All
Level Range: All Levels

One of the most coveted and powerful weapons by Soldiers, Fixers and Agents is a Perennium weapon. They are not hard to make and the requirements to equip one is reasonable. The lowest QL which can be produced is QL 50, up to QL 200. Their attack rate is fast, and the damage beats pretty much any Rubi-Ka weapon. 

Materials needed: 

QL 50 or higher 
Spirit Tech Apparatus: Double Barrel
Spirit Tech Apparatus: Long Muzzle
Spirit Tech Apparatus: Short Muzzle 

QL 50 or higher 
Sheet of Perennium 

QL 50 or higher 
Nano-Charged Assault Rifle or Nano-Charged Rifle 

QL 50 or higher 
Hacker Tool 

QL 50 or higher 
Perennium Bolts


The Spirit Tech Apparatus: Short Muzzle/Long Muzzle/Double Barrel items can found on Mortiig dyna bosses. Each will determine what the weapon will become. 


Spirit Tech Apparatus: Double Barrel -> Perennium Blaster (Solider Only)
Spirit Tech Apparatus: Long Muzzle   -> Perennium Sniper (Agent only)   
Spirit Tech Apparatus: Short Muzzle  -> Perennium Beamer (Fixer Only)   


Sheet of Perennium and Perennium Bolts are common loot from Hecklers, usually found at the brink of any SL area. You can easily find a Nano-Charged Assault Rifle or Nano-Charged Rifle in Rubi-Ka missions. Hacker Tool can be purchased from general stores. 

Now lets get to the process of making the weapon: 
<font color = yellow>This process will require 7x Weapon Smithing and 5x Mechanical engineering skill.</font> 

Spirit Tech Apparatus: Double Barrel   +   Sheet of Perennium   =   Double Perennium Barrel 
Spirit Tech Apparatus: Long Muzzle   +   Sheet of Perennium   =   Long Perennium Muzzle 
Spirit Tech Apparatus: Short Muzzle   +   Sheet of Perennium   =   Short Perennium Muzzle 

<font color = yellow>You'll need 7x Breaking and Entering skill for this step.</font>

Nano-Charged Assault Rifle   +   Hacker Tool   =   Hacked Nano-Charged Assault Rifle 
Nano-Charged Rifle   +   Hacker Tool   =   Hacked Nano-Charged Rifle 

<font color = yellow>This process will require 7x Weapon Smithing and 5x Mechanical engineering skill.</font> 

Hacked Nano-Charged Assault Rifle +  Spirit Tech Apparatus: Double Barrel   =   Half-Finished Perennium Blaster 
Hacked Nano-Charged Rifle + Spirit Tech Apparatus:Long Perennium Muzzle   =   Half-Finished Perennium Sniper 
Hacked Nano-Charged Assault Rifle + Spirit Tech Apparatus: Short Perennium Muzzle   =   Half-Finished Perennium Beamer 

<font color = yellow>This process will require 7x Weapon Smithing and 5x Mechanical engineering skill.</font>

Half-Finished Perennium Blaster  +  Perennium Bolts   =   Perennium Blaster (Solider Only)
Half-Finished Perennium Sniper   +  Perennium Bolts   =   Perennium Sniper (Agent only)
Half-Finished Perennium Beamer   +  Perennium Bolts   =   Perennium Beamer (Fixer Only
";

$pernweps_txt = Links::makeLink("Making Perennium Weapons", $pernweps_txt); 
if($type == "msg") 
$this->send($pernweps_txt, $sender); 
elseif($type == "all") 
$this->send($pernweps_txt); 
else 
$this->send($pernweps_txt, "guild"); 
?>