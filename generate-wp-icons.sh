#!/bin/bash

set -e

# === Configuration ===
SOURCE_IMAGE="./public/img/logo.png"   # Path to original logo
ASSETS_DIR="../assets"                 # SVN assets directory
ICON_128="$ASSETS_DIR/icon-128x128.png"
ICON_256="$ASSETS_DIR/icon-256x256.png"
SVN_COMMIT_MESSAGE="Add plugin icons"

# === Helper ===
error_exit() {
  echo -e "\033[0;31m❌ Error: $1\033[0m"
  exit 1
}

success_msg() {
  echo -e "\033[0;32m✅ $1\033[0m"
}

info_msg() {
  echo -e "\033[1;34m➡ $1\033[0m"
}

# === Checks ===
if [[ ! -f "$SOURCE_IMAGE" ]]; then
  error_exit "Source image '$SOURCE_IMAGE' not found"
fi

if ! command -v sips &> /dev/null; then
  error_exit "macOS 'sips' tool not found. This script requires macOS."
fi

if [[ ! -d "$ASSETS_DIR/.svn" ]]; then
  error_exit "'$ASSETS_DIR' is not an SVN directory (missing .svn folder)"
fi

# === Create icons ===
info_msg "Generating plugin icons from $SOURCE_IMAGE..."

sips -Z 128 "$SOURCE_IMAGE" --out "$ICON_128" > /dev/null || error_exit "Failed to create 128x128 icon"
sips -Z 256 "$SOURCE_IMAGE" --out "$ICON_256" > /dev/null || error_exit "Failed to create 256x256 icon"

success_msg "Created: $ICON_128"
success_msg "Created: $ICON_256"

# === SVN add and commit ===
cd "$ASSETS_DIR"
info_msg "Adding icons to SVN..."
svn add --force icon-128x128.png icon-256x256.png > /dev/null || error_exit "svn add failed"

svn status

read -p "Do you want to commit the icons to SVN now? (y/n): " CONFIRM_COMMIT
if [[ "$CONFIRM_COMMIT" == "y" ]]; then
  svn commit -m "$SVN_COMMIT_MESSAGE" || error_exit "SVN commit failed"
  success_msg "Icons committed to SVN."
else
  info_msg "SVN commit skipped."
fi
