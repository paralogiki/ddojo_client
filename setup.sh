#!/bin/bash
echo "Installing required packages: git chromium-browser php"
echo "------------------------------------------------------"
#sudo apt-get install git chromium-browser php xdotool
GIT_TEST=`which git`
if [ "$GIT_TEST" != "/usr/bin/git" ]; then
	echo "/usr/bin/git not found, exiting"
	exit
fi
CHROMIUM_TEST=`which chromium-browser`
if [ "$CHROMIUM_TEST" != "/usr/bin/chromium-browser" ]; then
	echo "/usr/bin/chromium-browser not found, exiting"
	exit
fi
PHP_TEST=`which php`
if [ "$PHP_TEST" != "/usr/bin/php" ]; then
	echo "/usr/bin/php not found, exiting"
	exit
fi
XDOTOOL_TEST=`which xdotool`
if [ "$XDOTOOL_TEST" != "/usr/bin/xdotool" ]; then
	echo "/usr/bin/xdotool not found, exiting"
	exit
fi
echo "-----------------------------------"
echo "Please enter your display ID below:"
read -p "Display ID > " displayid
if [ "$displayid" = "" ]; then
	echo "Display ID must be specified"
	exit
fi
mkdir -p ~/.config/ddojo
cat << EndOfHereDoc > ~/.config/ddojo/client.json
{"display_id":"$displayid"}
EndOfHereDoc
echo "Setup is complete, run 'php client.php'"
