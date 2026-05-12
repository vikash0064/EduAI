#!/bin/bash

# watch-push.sh
# Watches for file changes in the current directory and triggers push.sh
# Requires fswatch. If not installed, you can use: brew install fswatch

if ! command -v fswatch &> /dev/null
then
    echo "fswatch could not be found. Please install it using: brew install fswatch"
    exit
fi

echo "Watching for file changes... Press Ctrl+C to stop."

# Watch the directory, excluding .git, node_modules, and vendor folders
fswatch -o -e "\.git" -e "node_modules" -e "vendor" . | while read num ; do
    echo "Change detected. Triggering push.sh..."
    ./push.sh
done
