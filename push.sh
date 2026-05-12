#!/bin/bash

# push.sh
# Automatically stages all changes, commits with a timestamp, and pushes to GitHub main branch.

echo "Staging changes..."
git add .

timestamp=$(date "+%Y-%m-%d %H:%M:%S")
commit_message="Auto-commit: $timestamp"

echo "Committing with message: $commit_message"
git commit -m "$commit_message"

echo "Pushing to origin main..."
git push origin main

echo "Push complete!"
