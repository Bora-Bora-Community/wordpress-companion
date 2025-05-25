#!/bin/bash

set -e

# === Configuration ===
PLUGIN_SLUG="bora-bora"
SVN_URL="https://plugins.svn.wordpress.org/$PLUGIN_SLUG"
SVN_USER="boraboraio"
GIT_PLUGIN_DIR="."
TMP_SVN_DIR="/tmp/$PLUGIN_SLUG-svn"
TMP_BUILD_DIR="/tmp/$PLUGIN_SLUG-build"
ZIP_OUTPUT="./bora_bora.zip"

# === Helper functions ===
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

# === Extract version from plugin header ===
VERSION=$(sed -nE 's/^[[:space:]]*\*[[:space:]]*Version:[[:space:]]*([0-9.]+).*$/\1/p' "$GIT_PLUGIN_DIR/bora_bora.php") || error_exit "Failed to read version from plugin file"
if [[ -z "$VERSION" ]]; then
  error_exit "Version is empty – check the header in bora_bora.php"
fi
info_msg "Detected version: $VERSION"

# === User prompts ===
echo "== What do you want to do? =="
read -p "1. Create ZIP file? (y/n): " DO_ZIP
read -p "2. Push full release to SVN (with new tag)? (y/n): " DO_SVN_FULL

# Ask for trunk-only deployment only if full release is not selected
DO_SVN_TRUNK_ONLY="n"
if [[ "$DO_SVN_FULL" != "y" ]]; then
  read -p "3. Push only to trunk (no new tag)? (y/n): " DO_SVN_TRUNK_ONLY
fi

# === Create ZIP file ===
if [[ "$DO_ZIP" == "y" ]]; then
  info_msg "Creating ZIP file..."

  ZIP_PARENT_DIR="../"
  BUILD_DIR="$ZIP_PARENT_DIR/bora_bora"
  ZIP_FILE="$ZIP_PARENT_DIR/bora_bora.zip"

  rm -rf "$BUILD_DIR" "$ZIP_FILE" || error_exit "Failed to remove old ZIP files"
  mkdir -p "$BUILD_DIR" || error_exit "Failed to create build directory"

  rsync -av ./ "$BUILD_DIR/" \
    --exclude=".git" \
    --exclude=".github" \
    --exclude=".idea" \
    --exclude="deploy.sh" || error_exit "rsync failed during ZIP creation"

  cd "$ZIP_PARENT_DIR" || error_exit "Failed to switch to ZIP directory"
  zip -r "bora_bora.zip" "bora_bora" > /dev/null || error_exit "ZIP creation failed"

  rm -rf "$BUILD_DIR" || error_exit "Failed to remove temporary folder $BUILD_DIR"

  success_msg "ZIP file created at: $ZIP_FILE"
else
  info_msg "ZIP creation skipped"
fi

# === Full SVN deployment (with tag) ===
if [[ "$DO_SVN_FULL" == "y" ]]; then
  info_msg "Starting full SVN deployment (with tag)..."

  rm -rf "$TMP_SVN_DIR"
  svn checkout "$SVN_URL" "$TMP_SVN_DIR" --username "$SVN_USER" || error_exit "SVN checkout failed"

  rm -rf "$TMP_SVN_DIR/trunk/*"
  rsync -av --exclude=".git" --exclude=".github" --exclude=".idea" "$GIT_PLUGIN_DIR/" "$TMP_SVN_DIR/trunk/" || error_exit "rsync failed for trunk"

  mkdir -p "$TMP_SVN_DIR/tags/$VERSION"
  rsync -av "$TMP_SVN_DIR/trunk/" "$TMP_SVN_DIR/tags/$VERSION/" || error_exit "Failed to copy tag directory"

  cd "$TMP_SVN_DIR"
  svn add --force * --auto-props --parents --depth infinity -q
  svn status

  read -p "Do you want to commit this full release now? (y/n): " CONFIRM_COMMIT
  if [[ "$CONFIRM_COMMIT" == "y" ]]; then
    svn commit -m "Release $VERSION" --username "$SVN_USER" || error_exit "SVN commit failed"
    success_msg "SVN commit completed"
  else
    info_msg "Commit aborted"
  fi
fi

# === Trunk-only SVN update ===
if [[ "$DO_SVN_TRUNK_ONLY" == "y" ]]; then
  info_msg "Starting SVN trunk-only update..."

  rm -rf "$TMP_SVN_DIR"
  svn checkout "$SVN_URL/trunk" "$TMP_SVN_DIR" --username "$SVN_USER" || error_exit "SVN trunk checkout failed"

  rsync -av --exclude=".git" --exclude=".github" --exclude=".idea" "$GIT_PLUGIN_DIR/" "$TMP_SVN_DIR/" || error_exit "rsync failed for trunk-only"

  cd "$TMP_SVN_DIR"
  svn add --force * --auto-props --parents --depth infinity -q
  svn status

  read -p "Do you want to commit this trunk update now? (y/n): " CONFIRM_COMMIT_TRUNK
  if [[ "$CONFIRM_COMMIT_TRUNK" == "y" ]]; then
    svn commit -m "Update trunk for version $VERSION" --username "$SVN_USER" || error_exit "SVN trunk commit failed"
    success_msg "SVN trunk-only commit completed"
  else
    info_msg "Trunk-only commit aborted"
  fi
fi
