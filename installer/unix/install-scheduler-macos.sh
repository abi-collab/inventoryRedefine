#!/usr/bin/env bash
# Install launchd agent to run Laravel scheduler every minute (macOS)
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/../.." && pwd)"
PHP="$(command -v php)"
LABEL="com.kycc.inventory.scheduler"
PLIST="$HOME/Library/LaunchAgents/${LABEL}.plist"

cat > "$PLIST" <<EOF
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
  <key>Label</key><string>${LABEL}</string>
  <key>ProgramArguments</key>
  <array>
    <string>${PHP}</string>
    <string>${ROOT}/artisan</string>
    <string>schedule:run</string>
  </array>
  <key>WorkingDirectory</key><string>${ROOT}</string>
  <key>StartInterval</key><integer>60</integer>
  <key>StandardOutPath</key><string>${ROOT}/storage/logs/scheduler.out.log</string>
  <key>StandardErrorPath</key><string>${ROOT}/storage/logs/scheduler.err.log</string>
</dict>
</plist>
EOF

launchctl unload "$PLIST" 2>/dev/null || true
launchctl load "$PLIST"
echo "Installed ${LABEL}. Daily sync:supabase runs via Laravel schedule at 02:00."
