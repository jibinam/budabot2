screen -d -m -S bots

# bot1
screen -X -S bots screen -t bot1
screen -X -S bots -p bot1 stuff "cd /home/user/bots/bot1;
"
screen -X -S bots -p bot1 stuff "./chatbot.sh;
"

# bot2
screen -X -S bots screen -t bot2
screen -X -S bots -p bot2 stuff "cd /home/user/bots/bot2;
"
screen -X -S bots -p bot2 stuff "./bot2.sh;
"