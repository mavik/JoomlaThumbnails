#!/bin/bash

set -e
mkdir -p build

cp mavik-thumbnails/pkg_mavik-thumbnails.xml build/pkg_mavik-thumbnails.xml
cp -r mavik-thumbnails/plugin build/plugin
cp -r mavik-thumbnails/libraries/image build/image
cp -r mavik-thumbnails/libraries/thumbnails build/thumbnails
cp -r mavik-thumbnails/libraries/masterminds build/masterminds

cd build
rm mavik-thumbnails.zip || true

cd plugin
zip -r ../plugin.zip *
cd ../image
zip -r ../image.zip *
cd ../thumbnails
zip -r ../thumbnails.zip *
cd ../masterminds
zip -r ../masterminds.zip *
cd ..
zip -r mavik-thumbnails.zip pkg_mavik-thumbnails.xml plugin.zip image.zip thumbnails.zip masterminds.zip

rm plugin.zip
rm image.zip
rm thumbnails.zip
rm masterminds.zip

rm -r plugin
rm -r image
rm -r thumbnails
rm -r masterminds
rm pkg_mavik-thumbnails.xml

echo -e "\e[32mDone'\e[0m"