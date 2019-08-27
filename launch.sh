#!/bin/bash
URL=$1
if [ "$URL" == "local" ]; then
	if [ -r "`pwd`/downloads/display.html" ]; then
		URL="file://`pwd`/downloads/display.html"
	else
		URL="error"
	fi
fi
if [ -z $URL ] || [ "$URL" == "error" ]; then
	URL="file://`pwd`/config_error.html"
fi
# kill all open chromium browsers
pkill -f -- "chromium-browser"
sleep 2
# open display html
/usr/bin/chromium-browser --app="$URL" > /dev/null 2>&1 &
sleep 3
# Focus last created chromium window and full screen
xdotool windowactivate --sync $(xdotool search --onlyvisible --class chromium-browser | tail -1) key F11
# Disable screen going black and screen turn off
xset s noblank
xset s off
# Disable Energy Star which would also turn off screen
xset -dpms
