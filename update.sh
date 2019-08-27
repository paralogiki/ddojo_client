#!/bin/bash
GIT_TEST=`which git`
if [ "$GIT_TEST" != "/usr/bin/git" ]; then
	echo "git is not install, please install that first"
	exit
fi
DD_HOME=~
DD_INST="$DD_HOME/ddojo_client"
cd $DD_INST
git pull
if [ ! -d "$DD_INST" ]; then
	echo "git clone failed, please try to install again"
	exit
fi
if [ ! -x  "$DD_SETUP" ]; then
	echo "Setup file is missing or not executable"
	exit
fi
echo "Update complete, please run: $DD_SETUP"
