<?php
$blob = "<header>::::: Official AO Code Of Conduct  :::::<end>\n\n
<font color = yellow>The following is pasted directly from Funcom.</font>

The following are the basic rules of conduct that govern player interaction and activity within anarchy online. Failure to act responsibly and comply with these rules may result in the termination of your account without any refund of any kind.

   1.You may not harass or threaten other players in any form while online in Anarchy Online. You may not use information you get in-game to harass people out of the game.

   2. You may not use any sexually explicit, harmful, threatening, abusive, defamatory, obscene, hateful, racially or ethnically offensive language. This includes all communication with other players.

   3. You may not impersonate any Funcom employee, including, but not limited to, any Customer Support people, nor may you impersonate any member of the ARK (Advisors of Rubi-Ka) organisation.

   4.You may not violate any local, state, national or international law or regulation for the location you are playing from.

   5.You may not arrange for the exchange or transfer of any pirated or illegal software while on Anarchy or the Anarchy Web site including the Anarchy official Forums.

   6.You will always follow the instructions of authorized personnel while in Anarchy Online. Authorized personnel are here defined as GMs and ARK members.

   7. You may not modify, or try to modify, any part of the Anarchy Online Client, Anarchy Online Server or any part of the Anarchy Web Pages located at http://www.anarchy-online.com

   8.You may not organize nor be a member of any clans or groups within Anarchy that are based on, or espouse, any racist, anti-religious, anti-ethnic, or other hate-mongering philosophy.

   9. You will not attempt to interfere with, hack into, or decipher any transmissions to or from the servers running Anarchy. You are however allowed to use status pages provided by Funcom.

  10.You may not give false information or intentionally hide any information when registering your Anarchy account on https://register.funcom.com.

  11.You will not exploit any bug in Anarchy and you will not communicate the existence of any such exploitable bug (bugs that grant the user unnatural or unintended benefits in game for a character of their profession and level), either directly or through public posting, to any other user of Anarchy. You will promptly report any such bug via the exploits@anarchy-online.com email address.

  12.You will not attempt to play or run Anarchy on any server that is not controlled or authorized by Funcom BV.

  13.You will not upload or transmit on Anarchy, or on the Anarchy Web Sites any copyrighted content that you do not own all rights to, unless you have the express written permission of the author or copyright holder.

  14.You will not create, use or provide any server emulator or other site where Anarchy may be played, and you will not post or distribute any utilities, emulators or other software tools related to Anarchy without the express written permission of Funcom BV.

The following things would be considered an 'abuse' in game:

<font color = yellow>Hate Mongering</font>

    Being active and/or participation in or propagation of Hate literature, behaviour, or propaganda related to real -world characteristics.

<font color = yellow>Sexual Abuse or Harassment</font>

    Making advances of a graphic and sexual nature. This includes virtual rape, overt sexual overtures, and stalking of a sexual nature.

<font color = yellow>Attempting to Defraud a Support Representative</font>

    Petitioning with untrue information with the intention of receiving benefits as a result. This includes reporting bug deaths, experience or item loss, or accusing other players of wrongdoing without basis for it.
Impersonating a Customer Service Representative

    Falsely representing yourself to another player as an ARK or a Funcom employee.

<font color = yellow>CS Personnel Abuse</font>

    Sending excessive /tells to a CS Representative, excessively using say or other channels to communicate to a CS Representative, making physical threats, or using abusive language against a CS Representative.
Using Threats of Retribution by GM Friends

    Attempting to convince another player that they have no recourse in a disagreement because favouritism is shown to one of the parties by Funcom or the ARK staff.

Note: We encourage role-playing in the game and on the role-playing conference, but remember that role-playing is no excuse for harassment. If you want to play an obnoxious role, you have a great responsibility to avoid hurting other people's feelings. These social guidelines take precedence over role-playing in a conflict.
  ";

$msg = bot::makeLink("Official Code Of Conduct per Funcom", $blob);
bot::send($msg, $sendto);

?>