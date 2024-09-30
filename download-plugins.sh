#!/bin/env bash
echo "Download and extract plugins"
echo 
# Get list of plugins from plugins.txt
PLUGIN_FILE="plugins.txt"
PLUGINS=$(cat $PLUGIN_FILE)
if [[ -z $PLUGINS ]]; then
    echo "No plugins found in $PLUGIN_FILE"
    exit 1
fi

# Download and extract plugins
for PLUGIN in $PLUGINS; do
    echo "$PLUGIN"
    PLUGIN_URL="https://downloads.wordpress.org/plugin/$PLUGIN.zip"
    echo "- Downloading $PLUGIN from $PLUGIN_URL"
    wget -qO /tmp/plugin.zip https://downloads.wordpress.org/plugin/$PLUGIN.zip && unzip -o /tmp/plugin.zip -d . >> /dev/null
    if [[ $? == 0 ]]; then
        echo "-- Extracted $PLUGIN"
    else
        echo "-- ERROR: Failed to extract $PLUGIN"
    fi
done
