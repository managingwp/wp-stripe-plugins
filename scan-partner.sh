#!/bin/env bash

echo "Extracting pp_partner"

# Get list of plugins from plugins.txt
PLUGIN_FILE="plugins.txt"
PLUGINS=$(cat $PLUGIN_FILE)
if [[ -z $PLUGINS ]]; then
    echo "No plugins found in $PLUGIN_FILE"
    exit 1
fi

# Scan each plugin
for PLUGIN in $PLUGINS; do
    echo "$PLUGIN"        
    # Extract pp_partner_ string
    PP_PARTNER_STRING=$(grep -R "pp_partner_" $PLUGIN)
    # Grab everything starting at pp_partner_ and ending at the next space, single quote or double quote
    PP_PARTNER_STRING=$(echo $PP_PARTNER_STRING | sed -n 's/.*pp_partner_\([^ "\x27]*\).*/\1/p')
    echo " -- $PP_PARTNER_STRING"
done