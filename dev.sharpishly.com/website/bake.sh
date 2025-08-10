#!/bin/bash

# Get the current directory
base_dir="$PWD"

# copy controller
controller="$base_dir/app/controllers/sample.php"
new_controller="$base_dir/app/controllers/$1.php"
sudo ./modify_and_copy.sh "$controller" Sample "$2" "$new_controller"

# copy model
model="$base_dir/app/models/SampleModel.php"
new_model="$base_dir/app/models/${2}Model.php"
model_name="${2}Model"
sudo ./modify_and_copy.sh "$model" SampleModel "$model_name" "$new_model"

# copy view
sudo cp -R "$base_dir/app/view/sample/" "$base_dir/app/view/$1"

# copy css & js
sudo cp -R "$base_dir/public/sample/" "$base_dir/public/$1"

# set ownership
sudo chown "$USER" -R "$(pwd)"
