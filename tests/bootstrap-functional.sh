#!/bin/bash
set -ev

echo 'variables_order = "EGPCS"' >> ~/.phpenv/versions/$(phpenv version-name)/etc/conf.d/travis.ini

# Setup virtual display
export DISPLAY=:99.0
sh -e /etc/init.d/xvfb start

# Download and start selenium
wget http://selenium-release.storage.googleapis.com/2.48/selenium-server-standalone-2.48.2.jar
java -jar selenium-server-standalone-2.48.2.jar -browserSessionReuse -singleWindow > /dev/null 2> selenium.log &

# Start webserver
nohup php -S 127.0.0.1:8080 -t tests/Functional/web/ > /dev/null 2> phpd.log &
sleep 3
