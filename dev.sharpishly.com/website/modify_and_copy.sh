#!/bin/bash

if [ $# -ne 4 ]; then
  echo "Usage: $0 <file_path> <old_string> <new_string> <new_file_path>"
  exit 1
fi

file_path="$1"
old_string="$2"
new_string="$3"
temp_file="/tmp/temp_file.txt"
new_file="$4"

# Sudo open the file and replace the old string with the new string
sudo sed "s/$old_string/$new_string/g" "$file_path" > "$temp_file"

# Sudo copy the modified file to the new directory with a new file name
sudo cp "$temp_file" "$new_file"

# Clean up temporary file
rm "$temp_file"

echo "File modified and copied successfully."
