#!/bin/sh

# Dependencies
command -v npm >/dev/null 2>&1 || { echo >&2 "NodeJS is required to build jQuery. Aborting."; exit 1; }

echo "Making sure submodules are cloned..."
git submodule update --init

echo "Building jQuery..."
cd vendor/jquery
npm install >/dev/null 2>&1
grunt >/dev/null 2>&1
cd ../..

echo "Make storage writable..."
chmod -R 0777 storage/views

echo "Soft linking squire assets..."
cd public/bundles
ln -s ../../bundles/squire/public squire