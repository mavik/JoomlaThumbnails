#!/bin/bash

set -e

rm -r joomla/libraries/mavik/image/src
rm -r joomla/libraries/mavik/thumbnails/src
rm -r joomla/libraries/masterminds/html5-php/src
rm -r joomla/plugins/content/mavik-thumbnails

ln -sr mavik-thumbnails/libraries/image/Image/src joomla/libraries/mavik/image/src
ln -sr mavik-thumbnails/libraries/thumbnails/Thumbnails/src joomla/libraries/mavik/thumbnails/src
ln -sr mavik-thumbnails/libraries/masterminds/html5-php/src joomla/libraries/masterminds/html5-php/src
ln -sr mavik-thumbnails/plugin joomla/plugins/content/mavik-thumbnails

echo -e "\e[32mDone'\e[0m"