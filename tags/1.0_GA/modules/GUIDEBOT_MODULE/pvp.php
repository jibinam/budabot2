<?php
$blob = "<header>::::: Guide to PVP :::::<end>\n\n
The ranks in AO are determined by 'PvP points' You get points when you win a fight (and are not too much below the rank of the enemy or too high above the rank of the enemy). You must not be in a team, if you want to win points. If you are teamed, you also won't lose any points.

The PvP titles:

PvP points: 1300 &gt; 1399 = &lt;without title&gt;

PvP points: 1400 &gt; 1499 = Freshman

PvP points: 1500 &gt; 1599 = Rookie

PvP points: 1600 &gt; 1699 = Apprentice

PvP points: 1700 &gt; 1799 = Novice

PvP points: 1800 &gt; 1899 = Neophyte

PvP points: 1900 &gt; 1999 = Experienced

PvP points: 2000 &gt; 2099 = Expert

PvP points: 2100 &gt; 2299 = Master

PvP points: 2300 &gt; 2499 = Champion

PvP points: 2500 and more = Grandmaster

The rules:

Every player starts with 1300 PvP points. This number can not be lower, so even if you die a hundred times in PvP, you will always have at least 1300 PvP points (thats like the baseline for PvP points).

Fights against players with a rank 100 points below or above your own have no effect on your points or the enemie's points.

2.1 Examples

e.g. a Rookie with 1550 points is killed by a title-less player. This has no effect on the points of the Rookie or the title-less player. Thats the most important part of the system!

If 2 Rookies fight each other then it looks a bit differently:

Rookie Pvpjunkie with 1550 points kills Rookie Iknewit with 1580 points.

After this battle, Rookie Pvpjunkie has 14 PvP points more and Rookie Iknewit has 14 PvP points less.

The titles would not change, since Rookie Pvpjunkie has 1564 after the fight and Rookie Iknewit has 1566 points - so he keeps his Rookie rank.

Another example:

Freshman Ikeelyou with 1490 PvP points kills Rookie Igotkeeled with 1510 PvP points.

The result is as follows:

Freshman Ikeelyou gets 14 points and is with 1504 PvP points a new Rookie, whereas Rookie Igotkeeled loses 14 points and gets demoted to Freshman with 1496 points.

If 2 players with both 1300 points (no title) fight, the result is that the winner gains 12 points and the loser doesnt lose anything, since he/she is already at 1300 points.

Freshmen lose 12 points, Rookies lose 14 points, Apprentice lose 16 points in case they die to a player whose rank has a significance to the own rank.

At the moment there is no way to check how many PvP points you have. The ranks are the only indicator for your points.


<font color=white>from http://ao.stratics.com</font>";

$msg = bot::makeLink("Guide to PVP", $blob);
bot::send($msg, $sendto);
?>