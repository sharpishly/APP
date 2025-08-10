#!/bin/bash

# Exit on any error
set -e

# Check if the script is run with sudo (required for package installation)
if [ "$EUID" -ne 0 ]; then
  echo "Please run this script with sudo to install Doxygen."
  exit 1
fi

# Detect the operating system
OS=$(uname -s)

# Install Doxygen based on the operating system
if [ "$OS" = "Linux" ]; then
  if command -v apt-get >/dev/null 2>&1; then
    # Debian/Ubuntu-based systems
    echo "Installing Doxygen on Debian/Ubuntu..."
    apt-get update
    apt-get install -y doxygen
  elif command -v yum >/dev/null 2>&1; then
    # Red Hat/CentOS-based systems
    echo "Installing Doxygen on Red Hat/CentOS..."
    yum install -y doxygen
  else
    echo "Unsupported Linux package manager. Please install Doxygen manually."
    exit 1
  fi
elif [ "$OS" = "Darwin" ]; then
  # macOS
  if command -v brew >/dev/null 2>&1; then
    echo "Installing Doxygen on macOS using Homebrew..."
    brew install doxygen
  else
    echo "Homebrew not found. Please install Homebrew or Doxygen manually."
    exit 1
  fi
else
  echo "Unsupported operating system: $OS"
  exit 1
fi

echo "Doxygen installed successfully!"

# Create a basic Doxygen configuration file in the docs folder
DOXYFILE="docs/Doxyfile"
if [ ! -d "docs" ]; then
  echo "Creating docs folder..."
  mkdir -p docs
fi

echo "Creating Doxygen configuration file at $DOXYFILE..."
cat > "$DOXYFILE" << EOL
# Doxyfile for Sharpishly project documentation

DOXYFILE_ENCODING      = UTF-8
PROJECT_NAME           = "Sharpishly Project"
OUTPUT_DIRECTORY       = docs/
CREATE_SUBDIRS         = YES
OUTPUT_LANGUAGE        = English
GENERATE_HTML          = YES
GENERATE_LATEX         = NO
INPUT                  = .
FILE_PATTERNS          = *.py
RECURSIVE              = YES
EXTRACT_ALL            = YES
EXTRACT_PRIVATE        = YES
EXTRACT_STATIC         = YES
SOURCE_BROWSER         = YES
GENERATE_TREEVIEW      = YES
OPTIMIZE_OUTPUT_FOR_C  = NO
Sharpishly_DOCSTRING_STYLE = YES
EOL

echo "Doxygen configuration file created at $DOXYFILE"

# Run Doxygen to generate documentation
if command -v doxygen >/dev/null 2>&1; then
  echo "Running Doxygen to generate documentation..."
  doxygen "$DOXYFILE"
  echo "Documentation generated in docs/html/"
else
  echo "Doxygen not found. Please ensure it is installed correctly."
  exit 1
fi