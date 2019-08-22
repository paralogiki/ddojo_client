#!/bin/bash
URL=$1
if [ -z $URL ]; then
	URL="file://`pwd`/config_error.html"
fi
/usr/bin/chromium-browser --app="$URL" > /dev/null 2>&1
sleep 1
xdotool keydown F11
xdotool keyup F11
