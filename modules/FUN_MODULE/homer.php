<?php

   /*
   ** Author: Derroylo (RK2)
   ** Description: Shows a random homer quote (Ported over from a bebot plugin written by Xenixa (RK1))
   ** Version: 1.0
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 24.07.2006
   ** Date(last modified): 24.07.2006
   **
   ** Copyright (C) 2006 Carsten Lohmann
   **
   ** Licence Infos:
   ** This file is part of Budabot.
   **
   ** Budabot is free software; you can redistribute it and/or modify
   ** it under the terms of the GNU General Public License as published by
   ** the Free Software Foundation; either version 2 of the License, or
   ** (at your option) any later version.
   **
   ** Budabot is distributed in the hope that it will be useful,
   ** but WITHOUT ANY WARRANTY; without even the implied warranty of
   ** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   ** GNU General Public License for more details.
   **
   ** You should have received a copy of the GNU General Public License
   ** along with Budabot; if not, write to the Free Software
   ** Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
   */
   
$homer = array(
    "Your mom asked me to give you an apple instead",
    "Internet! Is that thing still around?",
    "Ah, beer, my one weakness. My Achille's heel, if you will.",
    "Okay, whatever to take my mind off my life.",
    "I voted for Prell to go back to the old glass bottle. Then I became deeply cynical. ( about voting )",
    "To find Flanders, I have to think like Flanders.",
    "Rock stars ... is there anything they don't know?",
    "Well, maybe if he had had better arch support, they wouldn't have caught 'im. ( about Jesus wearing sandals ).",
    "Ah, the college roadtrip. What better way to spread beer-fueled mayhem?",
    "All right, brain. You don't like me and I don't like you, but let's just do this and I can get back to killing you with beer.",
    "All right, let's not panic. I'll make the money by selling one of my livers. I can get by with one.",
    "America's health care system is second only to Japan ... Canada, Sweden, Great Britain ... well, all of Europe. But you can thank your lucky stars we don't live in Paraguay!",
    "If there's one thing I've learned, it's that life is one crushing defeat after another until you just wish Flanders was dead.",
    "Dear Lord, the gods have been good to me. As an offering, I present these milk and cookies. If you wish me to eat them instead, please give me no sign whatsoever ... thy will be done. (munch munch munch)",
    "The girls of the internet. Ooh, I'd go online with them anyday! ( Looking at a \"nudie deck\" )",
    "If he is so smart, how come he is dead?",
    "This kid's a wonder!. He organized all the law suits against me into one class action suit.",
    "I have to work overtime at work instead of spending time with my wife and kids, which is what I want.",
    "Aaw! it's so hard to get to 500 words ( Homer, the food critic ).",
    "The food was not undelicious.",
    "I'll tell people what to think. Now you tell me what to think.",
    "I hope you cut me better than you cut these string beans.",
    "And how is education supposed to make me feel smarter? Besides, every time I learn something new, it pushes some old stuff out of my brain. Remember when I took that home winemaking course, and I forgot how to drive?",
    "Aw, Dad, you've done a lot of great things, but you're a very old man, and old people are useless.",
    "If something goes wrong at the plant, blame the guy who can't speak English.",
    "Lord, I know I shouldn't eat Thee, but ... (munch munch munch) mmm ... sacrelicious.",
    "Awww, 20 dollars?!? I wanted a peanut.",
    "I don't apologize. I am sorry Lisa, that's the way I am.",
    "If it doesn't have siamese twins in a jar, it is not a fair.",
    "WHO IS FONZY!?! Don't they teach you anything at school?",
    "It's twice the work of a deadbeat dad. ( about spending a saturday with kids ).",
    "God cannot be EVERYWHERE, right? ( Homer as Adam in a dream ).",
    "Screw that squeaky stuff. I want some hard antacid for my kid.",
    "What's keeping Joan Rivers alive?",
    "Ooh! sensory depravation kicks ass!",
    "Oh! look at that car burn! Does it get any better than this?",
    "Bart, a woman is like beer. They look good, they smell good, and you'd step over your own mother just to get one!",
    "Bart, you're saying butt-kisser like it's a bad thing!",
    "Beer. Now there's a temporary solution.",
    "Marge, can we go home? All this fresh air is making my hair move and I don't know how long I can complain. ",
    "Black, marbelized with a liquid center. The Stealth Bowler. The pins don't know what hit 'em.",
    "Dear Homer, IOU one emergency donut. Signed Homer. Bastard! He's always one step ahead.",
    "To be loved, you have to be nice to others EVERYDAY!. To be hated, you don't have to do squat. ( advice to Mr.Burns ).",
    "Do I know what rhetorical means?",
    "Do you want to change your name to Homer, Jr.? The kids can call you Hoju!",
    "Does whisky count as beer?",
    "Don't eat me. I have a wife and kids. Eat them.",
    "Don't mess with the dead, boy, they have eerie powers.",
    "Don't worry, son. I'm sure he's up in heaven right now laughing it up with all the other celebrities : John Dilinger, Ty Cobb, Joseph Stalin.",
    "Donuts. Is there anything they can't do?",
    "Are you sure you're an accredited and honored pornographer?",
    "I can't believe that someone I've never heard of wants to hang out with a guy like me.",
    "Facts are meaningless. You could use facts to prove anything that's even remotely true!",
    "First you don't want me to get the pony, then you want me to take it back. Make up your mind.",
    "Getting out of jury duty is easy. The trick is to say you're prejudiced against all races. ",
    "God bless those pagans.",
    "Ah! I was voted most likely to be a mental patient or a hillbilly or a chimpanzee. ( Homer, the Outsider Artist )",
    "Stupid ice. I always knew I'll get stuck in something.",
    "I get weary in this sexually suggestive dancing.",
    "Marge, I think I'll remember my own LIFE!",
    "Marge, your paintings look like the things they look like.",
    "What is a wedding? Webster's Dictionary defines a wedding as \"The process of removing weeds from one's garden.\" ( giving a lecture on marriage ).",
    "Good drink ... good meat ... good God, let's eat!",
    "Ha ha! Look at this country! You are gay!? Ha ha!",
    "Heh Heh Heh! Lisa! Vampires are make believe, just like elves and gremlins and eskimos!",
    "Here's to alcohol : The cause of ... and answer to all of life's problems.",
    "Hey, I asked for ketchup! I'm eatin' salad here!",
    "I am so smart, I am so smart, s-m-r-t ... I mean s-m-A-r-t.",
    "I bet Einstein turned himself into all sorts of colors before he invented the light bulb.",
    "I can't believe it! Reading and writing actually paid off!",
    "I don't want to go, so if he asks me to go, I'll just say, 'Yes!'",
    "I guess you might say he barking up the wrong ... bush.",
    "I hope I didn't brain my damage.",
    "I know what you're saying, Bart. When I was young, I wanted an electric football machine more than anything else in the world, and my parents bought it for me, and it was the happiest day of my life. Well, goodnight.",
    "I know you can read my thoughts, boy : Meow, Meow, Meow, Meow, Meow, Meow, Meow, Meow, Meow, Meow, Meow, Meow, Meow, Meow, Meow, Meow.",
    "I like my beer cold ... my TV loud ... and my homosexuals flaming.",
    "I promised my boy one simple thing : lots of riches, and that man broke my promise!",
    "I saw this movie about a bus that had to SPEED around a city, keeping its SPEED over fifty, and if its SPEED dropped, it would explode! I think it was called, ?he Bus That couldn't Slow Down.",
    "I think the saddest day of my life was when I realized I could beat my Dad at most things, and Bart experienced that at the age of four.",
    "I wonder where Bart is, his dinner's getting all cold ...... and eaten.",
    "I won't sleep in the same bed with a woman who thinks I'm lazy! I'm going right downstairs, unfold the couch, unroll the sleeping ba- uh, goodnidght.",
    "If something is to hard to do, then it's not worth doing. You just stick that guitar in the closet next to your shortwave radio, your karate outfit and your unicycle and we'll go inside and watch TV.",
    "If they think I'm going to stop at that stop sign, they're sadly mistaken!",
    "If this were really a nuclear war we'd all be dead meat by now.",
    "I'll handle this ... the only danger in space is if we land on the terrible Planet of the Apes ... wait a minute. Statue of Liberty ... THAT WAS OUR PLANET! YOU MANIACS! YOU BLEW IT UP! DAMN YOU! DAMN YOU ALL TO HELL!",
    "I'm a white male, age 18 to 49. Everyone listens to me, no matter how dumb my suggestions are.",
    "I'm going to the backseat of my car with the woman I love, and I won't be back for TEN MINUTES.",
    "I'm having the best day of my life, and I owe it all to not going to Church!",
    "I'm in a place where I don't know where I am!",
    "I'm just a technical supervisor who cared too much.",
    "I'm no supervising technician, I'm a technical supervisor.",
    "In America, first you get the sugar, then you get the power, then you get the women!",
    "It may be on a lousy channel, but the Simpsons are on TV!",
    "It's a good thing that beer wasn't shaken up any more, or I'd have looked quite the fool. An April fool, as it were.",
    "It's like something out of that twilighty show about that zone.",
    "It's not easy to juggle a pregnant wife and a troubled child, but somehow I managed to squeeze in 8 hours of TV a day.",
    "Just because I don't care doesn't mean I don't understand!",
    "Kids, kids. As far as Daddy's concerned, you're both potential murderers. ",
    "Kill my boss? Do I dare live out the American dream?",
    "Kill myself? Killing myself is the last thing I'd ever do. Now I have a purpose, a reason to live. I don't care who I have to face, I don't care who I have to fight, I will not rest until this street gets a stop sign!",
    "Let us all bask in television's warm glowing warming glow.",
    "Let us celebrate our agreement with the adding of chocolate to milk.",
    "Lisa, stop that racket! I'm trying to fix your mother's camera. Easy, easy. I think I'll need a bigger drill. ",
    "Lisa, the mob's working on getting your saxophone back, but we've also expanded into other important areas ... World domination. ",
    "Lord help me, I'm just not that bright.",
    "Lurlee your song touched me in so many ways ... and which way to the can?",
    "Marge! Look at all this great stuff I found at the Marina. It was just sitting in some guy's boat!",
    "Marge, it takes two to lie. One to lie and one to listen.",
    "Marge, please. Old people don't need companionship. They need to be isolated and studied so that it can be determined what nutrients they have that might be extracted for our personal use.",
    "Shut up, Brain, or I'll stab you with a Q-tip!",
    "Remember that postcard Grandpa sent us from Florida of that alligator biting that woman's bottom? That's right, we all thought it was hilarious. But it turns out we were wrong. That alligator was sexually harassing that woman.",
    "Marge, would you please tell Bart that I would just like to drink a glass of syrup like I do every morning?",
    "Marge, you being a cop makes you the man! Which makes me the woman -- and I have no interest in that, besides occasionally wearing the underwear, which as we discussed, is strictly a comfort thing.",
    "Me lose brain? Uh, oh! Ha ha ha! Why I laugh?",
    "Mr. Scorpio says productivity is up 2%, and it's all because of my motivational techniques, like donuts and the possibility of more donuts to come.",
    "No jokes, no taunting -- That kid's got bosoms! Somebody get me a wet towel!",
    "C'mere you butterball.",
    "No! No-no-no-no-no-no! Well, yes.",
    "No, no, no, Lisa. If adults don't like their jobs, they don't go on strike. They just go in every day and do it really half-assed. That's the American Way.",
    "Now Bart, since you broke Grandpa's teeth, he gets to break yours.",
    "Now go on, boy, and pay attention. Because if you do, someday, you may achieve something that we Simpsons have dreamed about for generations : You may outsmart someone!",
    "Oh look at me !!! I'm making people happy! I'm the magical man from happy land, with a gumdrop house on lollipop lane! Oh by the way ... I was being sarcastic.",
    "Trying is the first step towards failure.",
    "Oh no! What have I done? I smashed open my little boy's piggy bank, and for what? A few measly cents, not even enough to buy one beer. Wait a minute, lemme count and make sure ... not even close.",
    "Oh, everything's too damned expensive these days. This Bible cost 15 bucks! And talk about a preachy book! Everybody's a sinner! Except this guy.",
    "Oh, Lisa, you and your stories ... Bart's a vampire, beer kills brain cells. Now let's go back to that ... building ... thingie ... where our beds and TV ... is.",
    "Oh, people can come up with statistics to prove anything, Kent. 14% of people know that.",
    "OK, son. Just remember to have fun out there today, and if you lose, I'LL KILL YOU!",
    "Operator! Give me the number for 911!",
    "Read your town charter, boy. If food stuff should touch the ground, said food stuff shall be turned over to the village idiot? Since I don't see him around, start shoveling!",
    "Relax. What is mind? No matter. What is matter? Never mind!",
    "Remember as far as anyone knows, we're a nice normal family.",
    "Safety? But sir! If truth be known, I actually caused more accidents around here than any other employee, including a few doozies no one ever found out about.",
    "Simpson-Homer Simpson , he's the greatest guy in his-tor-y. From the town of Springfield, he's about to hit a chestnut tree. D'oh!",
    "Solid waste! I could kiss you! Bleh! Ew! Yeech! Ooh! I think this was pizza!",
    "Son, being popular is the most important thing in the whole world.",
    "Son, this is the only time I'm ever gonna say this. It is not okay to lose.",
    "Stealing! How could you?! Haven't you learned anything from that guy who gives those sermons at church? Captain Whats-his-name?",
    "That's it! You people have stood in my way long enough. I'm going to clown college!",
    "The lesson is : Our God is vengeful! O spiteful one, show me who to smite and they shall be smoten!",
    "The only danger is if they send us to that terrible planet of the apes.",
    "The strong must protect the sweet.",
    "There's a New Mexico?!?",
    "They have the Internet on computers, now?",
    "This donut has purple in the middle, purple is a fruit.",
    "This is absolutely the last funeral we ever take you kids to.",
    "This perpetual motion machine she made is a joke : It just keeps going faster and faster. Lisa, get in here! In this house, we obey the laws of THERMODYNAMICS!",
    "Movies are the only escape from the drudgery of work and family ... No offense.",
    "I am sick of running away. Did 'brave heart' run away? Did 'payback' run away? (to Mel Gibson)",
    "Just where do you think you are going, missy?(Lisa \"ascending\" into heaven)",
    "Uh huh. Uh huh. Okay. Um, can you repeat the part of the stuff where you said all about the ... things? Uh ... the things.",
    "A big mountain of sugar is too much for one man. I can see now why God portions it out in those little packets.",
    "I've got the presciption for you, Doctor ... another hot beef injection! ( Hands him a hot dog )",
    "Unlike most of you, I am not a nut.",
    "Wait a minute. I'm a guy like me!",
    "We monorail conductors are a crazy breed.",
    "Can't he be both, like the late Earl Warren? ( for Bart to be Chief Justice of the Supreme Court or a sleazy male stripper ).",
    "Well you know boys, a nuclear reactor is a lot like women. You just have to read the manual and press the right button. ",
    "Well, crying isn't gonna bring him back ... unless your tears smell like dog food. So you can either sit there crying and eating can after can of dog food until your tears smell enough like dog food to make your dog come back or you can go out there and find your dog.",
    "Well, I'm tired of being a wannabe league bowler. I wanna be a league bowler!",
    "Well, it's like the time that your cat Snowball got run over? Remember that, honey? Well, what I'm saying is all we have to do is go down to the pound and get a new jazzman. ",
    "Well, let's just call them, uh, Mr. X and Mrs. Y. So anyway, Mr.X would say, 'Marge, if this doesn't get your motor running, my name isn't \"Homer J. Simpson.\"",
    "Well, you can't go wrong with cocktail weenies. They taste as good as they look. And they come in this delicious red sauce. It looks like catsup - it tastes like catsup. But brother, it ain't catsup!",
    "We're gonna get a new TV. Twenty-one inch screen, realistic flesh tones, and a little cart so we can wheel it into the dining room on holidays.",
    "We're laughing with her, Marge. There's a big difference. Ha ha ha! ... with her. ",
    "What are you gonna do? Sick your dogs on me? Or your bees? Or dogs with bees in their mouth so when they bark they shoot bees at me?",
    "What do we need a psychiatrist for? We know our kid is nuts. ",
    "What the hey, I'll take the job.",
    "What's the point of going out, we're just going to end up back here anyway?",
    "When I first heard that Marge was joining the police academy, I thought it would be fun and zany, like that movie -- Spaceballs. But instead it was dark and disturbing like that movie, Police Academy.",
    "When I look at the smiles on all the children's faces. Just know they're about to jab me with something.",
    "When will I learn? The answer to life's problems aren't at the bottom of a bottle, they're on TV!",
    "Yeah Moe that team sure did suck last night. They just plain sucked! I've seen teams suck before, but they were the suckiest bunch of sucks that ever sucked! Oh, I gotta go, my damn weiner kids are listening.",
    "Yes, honey ... Just squeeze your rage up into a bitter little ball and release it at an appropriate time, like that day I hit the referee with the whiskey bottle.",
    "Lisa, remember me as I am - filled with murderous rage. (y2k disaster)",
    "You couldn't fool your mother on the foolingest day of your life if you had an electrified fooling machine.",
    "You know, Moe, my mom once said something that really stuck with me. She said, `Homer, you're a big disappointment,' and God bless her soul, she was really onto something.",
    "You know those balls that they put on car antennas so you can find them in the parking lot? Those should be on EVERY CAR!",
    "You know, my kids think you're the greatest. And thanks to your gloomy music, they've finally stopped dreaming of a future I can't possibly provide.",
    "You tried your best and you failed miserably. The lesson is 'never try'.",
    "You'll have to speak up, I'm wearing a towel.",
    "Your lives are in the hands of men no smarter than you or I, many of them incompetent boobs. I know this because I worked alongside them, gone bowling with them, watched them pass me over for promotions time and again. And I say ... This stinks!",
    "Now son, you don't want to drink beer. That's for Daddys, and kids with fake IDs.",
    "Marge, don't discourage the boy! Weaseling out of things is important to learn. It's what separates us from the animals ... except the weasel.",
    "If you really want something in life you have to work for it. Now quiet, they're about to announce the lottery numbers.",
    "To alcohol! The cause of - and solution to - all of life's problems!",
    "I want to share something with you - the three sentences that will get you through life. Number one, 'cover for me.'Number two, 'oh, good idea, boss. 'Number three, 'it was like that when I got here.",
    "Marge, you're as pretty as Princess Leah and as smart as Yoda.",
    "Step aside everyone! Sensitive love letters are my specialty. 'Dear Baby, Welcome to Dumpsville. Population : you.",
    "Son, when you participate in sporting events, it's not whether you win or lose : it's how drunk you get.",
    "Lisa, if the Bible has taught us nothing else - and it hasn't - it's that girls should stick to girls' sports, such as hot oil wrestling and foxy boxing and such and such.",
    "We live in a society of laws. Why do you think I took you to all those Police Academy movies? For fun? Well I didn't hear anybody laughin', did you?",
    "Marge send the kids to the neighbors. I'm coming home loaded.",
    "Oh, well, of course, everything looks bad if you remember it.",
    "I would kill everyone in this room for a drop of sweet beer.",
    "You must love this country more than I love a cold beer on a hot Christmas morning. ",
    "Alcohol is my way of life, and I aim to keep it.",
    "Honey, I am not the catch that I appear to be. (in Las Vegas)",
    "The only guys who were Hawaiian shirts are gay guys and big fat party animals. ",
    "Sweet Merciful Crap!",
    "Lisa do I have my pants on?!",
    "Excuse me Doctor, I think I know a little something about medicine. ",
    "Nacho, nacho man. I want to be a nacho man.",
    "I kicked a giant mouse in the butt! Do I have to draw you a picture?",
    "Hey, can you take the wheel for a second, I have to scratch my self in two places at once.",
    "Ooh, a graduate student huh? How come you guys can go to the moon but can't make my shoes smell good?",
    "Alcohol is a way of life, alcohol is my way of life, and I aim to keep it.",
    "Television - teacher, mother, secret lover!",
    "Good Things don't end in \"eum\", they end in \"Mania\" or \"Teria\"",
    "Carnies Built this country, the carnival part of it anyway.",
    "The Alien has a sweet Heavenly Voice ... Like Urkle, And he appears every Friday night ... Like Urkle.",
    "If god didn't want me to eat in church, he would've made gluttony a sin.",
    "I felt a surge of power, like god must feel, when he's holding a gun.",
    "All my life I've been an obese man trapped inside a fat man's body.",
    "My Bologna has a first name. It's H-O-M-E-R, my bologna has a second name. It's H-O-M-E-R.",
    "This ticket doesn't just give me a seat, it gives me the right, NO, the DUTY! to make a complete ass of myself.",
    "Jesus, Alla, Buddha ... I love you all!",
    "... and I'm not impressed easily ... VOW! a blue car!!!",
    "Don't worry honey, daddy will fix that broken animal.",
    "Hey, if you dont like it, go to Russia!",
    "Guys are always patting my bald head for luck, pinching my belly to hear my girlish laugh.",
    "Hahahahaha, I'm so funny.",
    "Yummy, Yummy, Yummy, I got love in my tummy. ",
    "Man it feels good to get out of that car! Oooo go-karts, come on every body, let's go!",
    "Maybe he is acting stupid to infiltrate an international gang of idiots. ( about a TV character named after him ).",
    "Hmm ... Fabulous house ... Well-behaved kids ... Sisters-in-law dead ... Luxury Sedan ...WOOHOO! I hit the jackpot! Marge dear, would you kindly pass me a donut.",
    "You don't know what its like, I'm the one out there everyday putting his ass on the line, and I'm not out of order!You're out of order! The whole freakin' system is out of order! You want the Truth? You want the TRUTH?! YOU CAN'T HANDLE THE TRUTH!",
    "I am 26 hours late for work. No time for Maggi.",
    "Who is this? ... ugly nose ... liver spot ... liver spot ... liver spot ... liver spot ...",
    "Is it normal to see Mr.Burn's face on a bowling ball?",
    "I know what is going on here. They did it to Jesus. Now they are doing it to me. \n Marge : Are you comparing yourself to our Lord? \n Homer : Only in bowling ability.",
    "Maggie, that was a perfect game. But you stepped a little over the line. So, I am taking off 5 points. ( Maggie at 295 in bowling ).",
    "Kids are great, Appu. You can teach them to hate the things you hate and they practically raise themselves now-a-days, you know, with the internet and all.",
    "Bart : Gee ... Sorry for being born.|| Homer : I've been waiting for so long to hear that.",
    "Because when you reach over and put your hand into a pile of goo, that was your best friend's face, you don't know what to do!",
    "FORGET IT MARGE! ITS CHINATOWN!",
    "Ahhh ... sweet pity. Where would my love life be without it?",
    "Sorry Mr Burns, but I don't go in for these backdoor shenanigans. Sure I'm flattered, maybe even a little curious, but the answer is no!",
    "I'm hittin' the road. Maybe I'll drop you a line some day from wherever I wind up in this crazy old world.",
    "Asleep at the switch? I wasn't asleep, I was drunk.",
    "Whoooa, that's hot. There isn't a man alive who wouldn't get turned on by that. Well, goodbye.",
    "Sure, IN theeoory, in theory communism works ...",
    "Kids, if he (Grandpa) starts acting weird, lead him down into the basement.",
    "Call Mr. Plow, that's my name, that name again, is Mr. Plow!",
    "Hey there, Blimpy Boy, flying through the sky so fancy ... free ...",
    "Where's my Burrito? Where's my Burrito?",
    "We are not criminals. We are just two crazy, mixed-up kids (con artists Homer and Bart).",
    "Well ... GOD conned me out of 6500 bucks for car repairs.",
    "I told you I find them boring (little league games).",
    "We'll be stealing from people we KNOW.",
    "You've just won 10 million from the publishers clearing dealy.",
    "60 cents!?! I could've made more money if I had gone to work.",
    "O.K. you can park my car, but remember, NO joy riding.",
    "Ooh! The magic is made of chimps.",
    "Get used to it honey. From now on, we'll be spelling everything with letters.",
    "This place is a blast. All we have to do is bear two hours of excruciating pain. Then it is all sun and surfing.",
    "You're not the only one that can abuse a non profit organization!",
    "Spine buster ... boring ... Oooh Kaleistromister!!!",
    "Flanders!?! That suit is a bit revealing, isn't it?",
    "Chesty Lerou ... Busty St.Claire ... Booby McBoob [Homer's suggestions for a namechange for Marge].",
    "Nobody snuggles with Max Power ( name change ).",
    "Thank YOU for getting me out of work.",
    "Did somebody say 'Num Num'???",
    "Sometimes you have to break the rules to free the heart.",
    "I should have paid attention to the side effects. It's all in here.",
    "Now I prepare my soul for an eternity of fire and poking.",
    "I am doing a walk-on. It is a show business thing. ( after bowling fame ).",
    "I know someone holier than Jesus. ( Flanders ).",
    "You can't depend on me all your life. You have to learn that there's a little Homer Simpson in all of us.",
    "The code of the schoolyard, Marge! The rules that teach a boy to be a man. Let's see. Don't tattle. Always make fun of those different from you. Never say anything, unless you're sure everyone feels exactly the same way you do. What else ...",
    "And there's nothing wrong with hitting someone when his back is turned.",
    "What? Those cute little monkeys? That's terrible. Who told you that? I can understand how they wouldn't let in those wild jungle apes, but what about those really smart ones who live among us? Who roller-skate and smoke cigars?",
    "And Lord, we are especially thankful for nuclear power, the cleanest, safest energy source there is. Except for solar, which is just a pipe dream. ",
    "Because sometimes the only way you can feel good about yourself is by making someone else look bad. And I'm tired of making other people feel good about themselves.",
    "Hey Flanders, it's no use praying. I already did the same thing, and we can't both win. ",
    "You heard me, I won't be in for the rest of the week. ... I told you! My baby beat me up! ... No, it is not the worst excuse I ever thought up.",
    "Ah, good ol' trustworthy beer. My love for you will never die.",
    "Dear God, just give me one channel.",
    "Pffft, English. Who needs that. I'm never going to England.",
    "Well, you bought all those smoke alarms, and we haven't had a single fire. ",
    "Quiet you kids. If I hear one more word, Bart doesn't get to watch cartoons, and Lisa doesn't get to go to college.",
    "Bart : I am through with working. Working is for chumps. \n Homer : Son, I'm proud of you. I was twice your age before I figured that out.",
    "Marge, I can't wear a pink shirt to work. Everybody wears white shirts. I'm not popular enough to be different.",
    "Oh, Marge, cartoons don't have any deep meaning. They're just stupid drawings that give you a cheap laugh.",
    "A job's a job. I mean, take me. If my plant pollutes the water and poisons the town, by your logic, that would make me a criminal.",
    "Here's good news! According to this eye-catching article, SAT scores are declining at a slower rate ... Hey, this is the only paper in America that's not afraid to tell the truth, that everything is just fine.",
    "Well, I know you love me, so you don't get squat. Hee hee hee. ( to Bart ).",
    "Lisa : It's not our fault our generation has short attention spans, Dad. We watch an appalling amount of TV. \n Homer : Don't you ever, EVER talk that way about television.",
    "Homer : Your mother has this crazy idea that gambling is wrong. Even though they say it's okay in the bible. \n Lisa : Really? Where? \n Homer : Uh ... Somewhere in the back.",
    "No matter how good you are at something, there's always about a million people better than you.",
    "Homer : Well, he's got all the money in the world, but there's one thing he can't buy. \n Marge : What's that? \n Homer : [thinks] A dinosaur",
    "Homer : I can't fake an interest in this, and I'm an expert at faking an interest in your kooky projects. \n Marge : What kooky projects? \n Homer : You know, the painting class, the first aid course, the whole Lamaze thing.",
    "I hate all the programs Marge likes, but it's no big deal. You know why? Whenever Marge turns on one of her \"non-violent\" programs, I take a walk. I go to a bar, I pound a few, then I stumble home in the mood for looooove.",
    "If something's hard to do, then it's not worth doing.",
    "Homer : Marge, where's that ... metal deely ... you use to ... dig ... food... \n Marge : You mean, a spoon? \n Homer : Yeah, yeah!",
    "Marge, there's an empty spot I've always had inside me. I tried to fill it with family, religion, community service, but those were dead ends! I think this chair is the answer.",
    "Marge : Homer, please don't make me choose between my man and my God, because you just can't win. \n Homer : There you go again, always taking someone else's side. Flanders ... the water department ... God ...",
    "God : Thou hast forsaken My Church! \n Homer : Uh, kind-of ... b-but ... \n God : But what \n Homer : I'm not a bad guy! I work hard, and I love my kids. So why should I spend half my Sunday hearing about how I'm going to Hell? \n God : [pause] Hmm ... You've got a point there.",
    "Bart : Dad, this money is from Montana Militia. This is not real money. \n Homer : It will be soon.",
    "I will live to be 42. Oh, only 42 ?!? I won't even live to see my children die.",
    "I am not crazy. It's the TV that's crazy. Aren't you, TV?",
    "Yeah, that's true. But the guy I REALLY hate is YOUR father. [to a psychiatrist].",
    "I am sane again . Look Marge! ... and I owe all this to ... THE SPRING BREAK!",
    "Marge : Homer, you are going to kill us all. \n Homer : Or DIE trying. ",
    "That's fine. There are plenty of other states where we are welcome. [to Florida officials after the alligator debacle].",
    "Arizona smells funny. [AZ and ND are the left-over states].",
    "I'll lucky if I could get just half-an-hour to get funky [Rocker Homer]. ",
    "Oh! I haven't changed since high school and suddenly I am uncool.",
    "People know your name. You don't know theirs. It's great [about being a rocker].",
    "It's mor important to be with my family than being cool.",
    "Guide : This man here is more than 200 MILLION years old. \n Homer : Pssst ... I got more bones than he has. If you're trying to impress me, you failed. \n Guide : It's not the bones ... \n Homer : You failed to impress me.",
    "Boy, everyone is stupid except me.",
    "You put the beer in the coconut and throw the can away.",
    "Are you sure this is the Sci-Fi Convention? It's full of nerds!",
    "Once the sun goes down, all the weirdos turn crazy!",
    "Oh, let's just say I had help from a little magic box.",
    "How about 'Screw Flanders' ?",
    "But I was going to loot you a present.",
    "They took the foam off the market because they found out it was poisonous, but if you ask me, if you're dumb enough to eat it, you deserve to die.",
    "Rev. Lovejoy : So Homer, please feel free to tell us anything. There's no judgment here. \n Homer : The other day I was so desperate for a beer I snuck into the football stadium and ate the dirt under the bleachers. \n Rev. Lovejoy : I cast thee out!",
    "Homer [thinks] Don't tell him you were at a bar! Gasp! But what else is open at night? [aloud] It's a pornography store. I was buying pornography. [thinks] Heh heh heh. I would'a never thought of that.",
    "God is teasing me! Just like he teased Moses in the desert!",
    "Marge : Homer, the plant called. They said if you don't show up tomorrow don't bother showing up on Monday. \n Homer : Woo-hoo! Four-day weekend!",
    "Wow. A baby and a free burger. Could this be the best day of my life?",
    "So I says, blue M&M, red M&M, they all wind up the same color in the end.",
    "Homer : Hey boy! Wanna play catch? \n Bart : No thanks dad. \n Homer : When a son doesn't want to play catch with his father something is definitely wrong. \n Grandpa : I'll play catch with you! \n Homer : Go home.",
    "Marge we're gettin some drive-through then doin it twice. ",
    "Ahh burn it, send it to hell!",
    "Yeah! He's a crazy nut! It's not about me being lazy! It's about him being a crazy nut!",
    "Marge : It looks like there's going to be twice as much love in this house. \n Homer : You mean we're going to start doing it in the morning?",
    "Give me some peace of mind or I'll mop the floor with you!",
    "My wife is not a doobie to be passed around! On our wedding day I promised to bogart her for life!",
    "We played Dungeons & Dragons for three hours! Then I was slain by an elf.",
    "Ive been muscled out of everything Ive ever done, including my muscule-for-hire business.",
    "Lenny says that I'm a (laughs) get this ... (laughs) ... a little SLOW ... (laughs, pauses) ... how come you're not laughing? Do you think I'm slow?",
    "You jive turkey. See? You got to sass it. Quit jivin' me, turkey. You got to sass it. A \"turkey\" is a bad person.",
    "You gave both dogs away?! You know how I feel about giving!",
    "Marge : Homer there's a man here who thinks he can help you! \n Homer : Batman? \n Marge : No he's a scientist. \n Homer : Batman's a scientist. \n Marge : He's not Batman!",
    "Marge : Have you noticed something about Bart? \n Homer : New glasses? \n Marge : No. It seems like something could be troubling him. \n Homer : Probably misses his old glasses.",
    "Marge : I want to get more involved in Bart's activities, but then I'd be afraid of smothering him. \n Homer : Yeah, and then we'd get the chair. \n Marge : That's not what I meant. \n Homer : Admit it Marge, it was.",
    "Default! The two sweetest words in the English language.",
    "Back you robots! Nobody ruins my family vacation but me! And maybe the boy.",
    "Burns : And this must be ... (reading card) little Brat. \n Bart : Bart. \n Homer : Don't correct the man, Brat.",
    "Kent : Well what do you say to the accusation that your group has been causing more crimes than it's been preventing? \n Homer : Oh, Kent I'd be lying if I said my men weren't committing crimes. \n Kent : (pause) Mmm, touché.",
    "Homer no function beer well without.",
    "Bart : We were just planning the father-son river rafting trip. \n Homer : He he. You don't have a son. ",
    "No offence Apu, but when they were handing out religions you must have been out taking a whizz.",
    "Take it easy Marge. How about if we dope you up real good? [Marge's fear of flying].",
    "Marge that's twice - I think you're spending entirely too much time with this woman.",
    "Homer : Here's your giraffe little girl. \n Ralph : I'm a boy. \n Homer : That's the spirit. Never give up.",
    "That's it! Being abusive to your family is one thing, but I will not stand by and watch you feed a hungry dog! Go to your room.",
    "Marge : This is terrible! How will the kids get home? \n Homer : I dunno. The Internet? [Springfield snow].",
    "I want to set the record straight - I thought the cop was a prostitute.",
    "That's it! If I'm gonna be trapped inside the house I gotta go out and buy some beer.",
    "Oh! it's 1 am. I better go home and spend some quality time the kids. [at Moe's]",
    "... lousy lovable dog ...",
    "FBI agent Scully : This is just a simple lie-detector test. I'll ask some simple questions and you should answer with yes or no. Do you understand? \n Homer : Yes. \n [ The machine blows up ].",
    "Bart : What if don't find anything?  \n Homer : Then we'll fake it and sell it to the Fox network. \n Bart : They'll buy anything. \n Homer : Now son, they also do a lot of quality shows ... ha ha ha ... They kill me.",
    "Homer : No one believes me. \n Bart : I believe you, dad. \n Homer : Then can you stop the cats from swearing?",
    "You changed me too. I am not the same money-driven workaholic that I once was. [nanny Sherry Bobbins]. ",
    "Homer : I'll have this sweet blood pudding. \n Bart : The secret ingredient is blood. \n Homer : Blood!?! Olakkkk ... instead I'll have this sweet brain and kidney pie. ");

if(preg_match("/^homer/i", $message)) {
	$randval = rand(1, sizeof($homer) - 1);
	$msg = $homer[$randval];
	bot::send($msg, "guild");
}
?>