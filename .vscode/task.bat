@echo off

REM Start browser-sync in a separate window
start "" browser-sync start --config .vscode/bs-config.js

REM Start Sass Watch
start "" sass --watch --style=compressed src/theme.scss:styles/theme.min.css --quiet

REM Start Listening to DebugLog in Wordpress
start "" powershell -Command "Get-Content ../../debug.log -Wait"

@REM REM Start rsync with remote site
@REM start "" watchexec rclone sync ./ remote:/path/to/theme/folder