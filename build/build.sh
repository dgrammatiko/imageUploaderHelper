#!/bin/sh
rm -rf ../packaging && mkdir ../packaging
rm -rf ../packages && mkdir ../packages
cp -r ../src ../packaging/src

cd ../packaging/src

zip -r -X plg_imageuploaderhelper.zip .

mv plg_imageuploaderhelper.zip ../../packages/plg_imageuploaderhelper.zip

