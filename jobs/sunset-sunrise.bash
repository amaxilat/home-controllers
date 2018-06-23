#!/bin/bash
HELP="Usage: sunset-sunrise.bash [sunset|sunrise]"

# Use $sunrise and $sunset variables to fit your needs. Example:
if [ "$#" -ne 1 ]; then
  echo $HELP
else
  # First obtain a location code from: https://weather.codes/search/
  # Insert your location. For example LOXX0001 is a location code for Bratislava, Slovakia
  LOCATION="GRXX0013:1:GR"
  # Obtain sunrise and sunset raw data from weather.com
  SUN_TIMES=$( lynx --dump  https://weather.com/weather/today/l/$LOCATION | grep "\* Sun" | sed "s/[[:alpha:]]//g;s/*//" )
  # Extract sunrise and sunset times and convert to 24 hour format
  SUNRISE=$(date --date="`echo $SUN_TIMES | awk '{ print $1}'` AM" +%R)
  SUNSET=$(date --date="`echo $SUN_TIMES | awk '{ print $2}'` PM" +%R)

  if [ "$1" == "sunset" ]; then
    echo "$SUNSET"
  elif [ "$1" == "sunrise" ]; then
    echo "$SUNRISE"
  else
    echo $HELP
  fi
fi
