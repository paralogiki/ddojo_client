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
pkill -f -- "chromium-browser"
sleep 2
/usr/bin/chromium-browser --app="$URL" > /dev/null 2>&1 &
sleep 3
xdotool windowactivate --sync $(xdotool search --onlyvisible --class chromium-browser | tail -1) key F11
