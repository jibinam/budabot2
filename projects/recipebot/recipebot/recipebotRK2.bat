@echo off
C:\Progra~1\EasyPHP1-8\php\php.exe -c "C:\Program Files\EasyPHP1-8\apache" recipebot.php rk2
echo Pause de 10 secondes...
ping -n 10 127.0.0.1 > nul
rem pause
start /min C:\budabot\zerobot\recipebot\recipebotRK2.bat
exit
