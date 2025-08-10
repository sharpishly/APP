#!/bin/bash

# Capitalize the first letter of $1
capitalized_name="$(echo ${1:0:1} | tr '[:lower:]' '[:upper:]')${1:1}"

controller="app/controllers/${1}.php"
echo "Removing Controller: $controller"

# Convert first letter of $1 to lowercase for model filename
model="app/models/${capitalized_name}Model.php"
echo "Removing Model: $model"

view="app/view/${1}/"
echo "Removing View: $view"

frontend="public/${1}/"
echo "Removing Frontend Assets: $frontend"

# Remove controller
[ -f "$controller" ] && sudo rm "$controller"

# Remove model
[ -f "$model" ] && sudo rm "$model"

# Remove view if it exists
[ -d "$view" ] && sudo rm -r "$view"

# Remove css & js if they exist
[ -d "$frontend" ] && sudo rm -r "$frontend"

echo "Cleanup completed!"

