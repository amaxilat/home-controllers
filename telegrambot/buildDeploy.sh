#!/usr/bin/env bash
./build.sh
docker build -t tbot:1.1 .
docker tag tbot:1.1 qopbot/tbot:1.1
docker push qopbot/tbot:1.1
