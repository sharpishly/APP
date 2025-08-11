#!/bin/bash

# Usage: ./duplicate.sh old_name new_name
# Example: ./duplicate.sh test hello

# Check arguments
if [ "$#" -ne 2 ]; then
    echo "Usage: $0 old_name new_name"
    exit 1
fi

OLD_NAME="$1"
NEW_NAME="$2"

# Get the current directory
BASE_DIR="$PWD"

# Define paths
OLD_CONTROLLER="$BASE_DIR/app/controllers/$OLD_NAME.php"
NEW_CONTROLLER="$BASE_DIR/app/controllers/$NEW_NAME.php"

OLD_MODEL="$BASE_DIR/app/models/${OLD_NAME^}Model.php"
NEW_MODEL="$BASE_DIR/app/models/${NEW_NAME^}Model.php"

OLD_VIEW="$BASE_DIR/app/view/$OLD_NAME"
NEW_VIEW="$BASE_DIR/app/view/$NEW_NAME"

OLD_PUBLIC="$BASE_DIR/public/$OLD_NAME"
NEW_PUBLIC="$BASE_DIR/public/$NEW_NAME"

# Duplicate controller
if [ -f "$OLD_CONTROLLER" ]; then
    cp "$OLD_CONTROLLER" "$NEW_CONTROLLER"
    sed -i "s/$OLD_NAME/$NEW_NAME/g" "$NEW_CONTROLLER"
    sed -i "s/${OLD_NAME^}/${NEW_NAME^}/g" "$NEW_CONTROLLER"
    echo "Duplicated controller: $NEW_CONTROLLER"
else
    echo "Controller not found: $OLD_CONTROLLER"
fi

# Duplicate model
if [ -f "$OLD_MODEL" ]; then
    cp "$OLD_MODEL" "$NEW_MODEL"
    sed -i "s/${OLD_NAME^}/${NEW_NAME^}/g" "$NEW_MODEL"
    echo "Duplicated model: $NEW_MODEL"
else
    echo "Model not found: $OLD_MODEL"
fi

# Duplicate view folder
if [ -d "$OLD_VIEW" ]; then
    cp -R "$OLD_VIEW" "$NEW_VIEW"
    echo "Duplicated view folder: $NEW_VIEW"
else
    echo "View folder not found: $OLD_VIEW"
fi

# Duplicate public folder
if [ -d "$OLD_PUBLIC" ]; then
    cp -R "$OLD_PUBLIC" "$NEW_PUBLIC"
    echo "Duplicated public folder: $NEW_PUBLIC"
else
    echo "Public folder not found: $OLD_PUBLIC"
fi

# Set ownership
sudo chown "$USER" -R "$NEW_CONTROLLER" "$NEW_MODEL" "$NEW_VIEW" "$NEW_PUBLIC" 2>/dev/null

echo "✔️ Duplication complete."
