<? 
$halloween_txt = "<header>::::: Halloween/Uncle Pumpkinhead Hunting  :::::<end>\n\n"; 
$halloween_txt = "

<font color='#69E61E'>:::::: Halloween Guide :::::                               </FONT>
                                                            
First of all, it's really dark and foggy out. You can solve the Fog problems by using:
F10 -> Environment -> Fog and Ground -> Fog Mode: Off

<font color='#69E61E'>Wild Uncle Pumpkin-Heads</font>

The Uncle Pumpkin-Head mobs hang out near tower fields. They are in the level range of the tower field. Thus, the highest level ones hang out near tower fields in PW, Belial Forest, etc. They are social, and they have a nasty stun and a weak DoT. Most importantly, they have a boatload of health, like a mission boss.

Because they spawn near tower fields, I would highly suggest turning Auto-Attack off. Otherwise, you might trigger a tower attack.

They drop the following items:

<font color='#69E61E'>Confirmed Drops in 2008</font>
<font color='#ff9900'>	
Oni T-shirt
Skullbox T-shirt
Zooming Witch T-shirt
Emoticon T-shirt (clickable)
ZOMG ALIUMS T-Shirt
E.P.E.E.N. Shirt
Pumpkinhead Doll
Pumpkin Basket
Tertium Quid
Sad/Happy panda mask (clickable)
Witch Mask
Hockey Mask
Mad Scientist Labcoat
Wooden Stake
Weird Looking Candy Cherry
Spooky Leet Nano (pet)
Sycophanted Glasses
Premium Sunburst Mk III
Pumpkin Helmet
BBQ Shoulder Pillow</font>

Lya's Sangi has about a 10-20% droprate:
Lya's Sangi Glasses - (NoDrop, Intelligence/Psychic) NanoC Init, ALL Nanoskills
Lya's Sangi Gloves - (NoDrop, Stamina/Agility) Multi Melee, Multi Ranged, Sharp Object, Grenade, Heavy Weapons, Melee Init, Ranged Init, Physical Init
Lya's Sangi Patch - (NoDrop, Agility/Sense) Agility, Sense
Lya's Sangi Shirt - (NoDrop, Stamina/Agility) 1HB, 2HB, 1HE, 2HB, Brawl, Dodge-Rng, Evade-ClsC
Lya's Sangi Sleeves - (NoDrop, Strength) Stamina, Strength, Bow
Lya's Sangi Slippers - (NoDrop, Stamina/Agility) Buffs MA, Piercing, Melee Energy, Sneak Attack, Fast Attack, Map Nav, Duck-Exp, Evade-ClsC
Lya's Sangi Trousers - (NoDrop, Stamina/Agility) Pistol, Rifle, MG/SMG, Shotgun, Assault Rifle, Duck-Exp, Dodge-Rng

Freedom Arms 3927 G2
Freedom Arms 3927 Chapman
Freedom Arms 3927 Guerrilla
Freedom Arms 3927 Notum

<font color='#69E61E'>Confirmed Drops 2007</font>
<font color='#ff9900'>The Midnight Pumpkin Bikini Top - NoDrop, Female only
The Midnight Pumpkin Bikini Bottom - NoDrop, Female only (thank god!)
The Midnight Pumpkin Boxer Shorts - NoDrop, non-Female only
Dear Liza's Shirt - NoDrop
The Grinning Devil shirt - NoDrop. Possibly two types?
Splat Shirt - NoDrop
Halloween Pitchfork - NoDrop, Dual wieldable
Gothic Lolita - Halloween Edition - NoDrop, Backslot
Wicked Tights of the West
Wicked Tights of the East
Good Stockings of the North</font> - YESDROP. These items can be changed to the next one down the list by right-clicking.


</font>
They also drop UNIQUE items called Weird Looking Candy Cherry. They can either give you a 24 hour bonus or polymorph you into a monster.

The QL of the Weird Cherry determines what bonus/polymorph you get:
<font color='#69E61E'>QL 1 = Anvian
QL 2 = Tac-Mutant (long tentacle arm)
QL 3 = Floater/Breiflabb
QL 4 = Add All Defense +10
QL 5 = Savage Medusa
QL 6 = Snake
QL 7 = Enigma
QL 8 = Primitive Chirop (flying grub)
QL 9 = Brain Dog
QL 10 = Horror</font>

WARNING: Do not eat the Cherries while wearing a Battle Suit! It will trigger the old 'Battle Suit Polymorph' crash bug and you will be dumped out, and possibly not able to come back until it wears off!

The Griefing Uncle Pumpkin-Head appears in PvP zones (possibly tower fields with low suppression gas). It drops the EPEEN shirt and may also drop Sunburst Mk III guns.

<font color='#69E61E'>Halloween Missions</font>

You can find Frankenleet and Draculeet all over the home cities of all factions. 
For Omnis, this means Rome Red and possibly Omni Ent. They ask you if you want a 'Trick' or 'Treat'.

'Trick' is a polymorph, and can change you into some of the more well-known denizens of Rubi-Ka like Tri-Plumbo or Morgan Le Fay. It lasts for quite a long time, and while you cannot attack in this form or use any other polymorphs while you are using one currently, you can cancel them and pick up other polymorphs at any time.

'Treat' gives you several choices. The 'I want old school phatz!' gives you a Kill Person mission where the target is an Uncle Pumpkin-Head. You have a chance of getting the Freedom Arms 3927 line, as well as Sunburst Mk III, Sunglasses of Syncopated Heartbeats. These no longer drop on RK. The Uncle Pumpkin-Head in the old school phatz mission will also drop a Weird Looking Candy Cherry quite often.

The other choice ('I heard you know where to get Halloween dolls and lanterns!')also gives you a Kill Person mission with Uncle Pumpkin-Head, but the rewards are:
<font color='#69E61E'>Pumpkinhead Doll
Pumpkin Basket
Pumpkin Helmet
Hanging Pumpkin Lanterns
Tertium Quid (floating Pumpkin Wen Wen) </font>

You can run one of each mission at a time. Once you complete the mission, you can return to the Draculeet or Frankenleet for another mission. You have no choice as to where the destination of the mission will be nor is any of the loot guaranteed.


";

$halloween_txt = $this->makeLink("Ferrel_s Halloween Guide", $halloween_txt); 
if($type == "msg") 
$this->send($halloween_txt, $sender); 
elseif($type == "all") 
$this->send($halloween_txt); 
else 
$this->send($halloween_txt, "guild"); 
?>

