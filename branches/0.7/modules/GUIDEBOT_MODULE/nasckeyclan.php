<? 
$nasckeyclan_txt = "<header>::::: Nascence Garden Key Quest CLAN :::::<end>\n\n"; 
$nasckeyclan_txt = "
<font color='#ff9933'><highlight>Nascence (Aban) Garden Key Quest</font></end>

<highlight>Guide suited for:</end> All Classes
<highlight>Faction:</end> Clan
<highlight>Level Range:</end> 1-50


This quest gives you the ability to travel between the Redeemed transit statues of Nascense without using insignias. You need at least 5 Insignias of Aban to complete it. Insignias drop from Unredeemed mobs and creatures which are not affiliated with the Redeemed faction. 

Insignia of Aban

Use the portal to Nascene in The Harbour area of Jobe City to get to Nascense Frontier, 
find Donna Red at 985 x 1760 and speak to her until she gives you an Ancient Device and a mission. 

Ancient Device

The next step is for you to get to the Redeemed village located on one of the floating islands (Silence garden exit)
If you have trouble with falling off the natural stone bridges (which will send you to the Athen Shire area on Rubi-Ka) you may be better off walking on them 

Speak to Ecclesiast Aban Fala at the Redeemed Village around 1890 x 690 and show him the Ancient Device. 

He then asks for proof of Aban's existence, show him an Insignia of Aban. 
Next he will asks you to find and mark the Statue of Aban. 
Get back to the statue that you used to reach him (closest to him, check the map above), use an Insignia to enter to the Garden of Aban. 

At the Garden speak to Sipius Aban Lux-Wel at 465 x 495 and he'll tell you more about the device
He asks you to save three souls with an Ancient Device. 

Take the second of these to get back to to Donna Red, then head west toward the Frontier Bridge. 
Near the bridge you should be able to find 3 Swift Silvertails, they're usually in groups. 

Shift right-click an Insignia of Aban on the Ancient Device to turn it into an Ancient Pattern Analyser and speak to the first Silvertail, putting the device over it's eyes. You will know that it worked once the Swift Silvertail becomes a Blessed Silvertail which should get a mission accomplished message, the device then will reset for the next one. Repeat the procedure for the 2nd and 3rd Swift Silvertails (which means that you need to use another 2 insignias). 

<highlight>Ancient Device</end>   +   <highlight>Insignia of Aban</end>   =   <highlight>Ancient Pattern Analyzer graced by the Faithful</end>

Go back to the Redeemed Garden and show the device to Sipius Aban Lux-Wel and he'll give you the key as a reward for your effort. 

The Key to the Garden of Aban

--------------------------------------------------------------------------------
Last updated on 10.06.2006 by Trgeorge
Information originally provided by Silq at the Official AO Forums. Additional information provided by Windguaerd (Map image from Sheremap v1.0.)
Courtesy of AO Universe
"
;
$nasckeyclan_txt = Text::makeLink("Elysium: Garden Key Quest Clan", $nasckeyclan_txt); 
if($type == "msg") 
$this->send($nasckeyclan_txt, $sender); 
elseif($type == "all") 
$this->send($nasckeyclan_txt); 
else 
$this->send($nasckeyclan_txt, "guild"); 
?>