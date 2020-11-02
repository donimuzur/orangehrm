@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../symfony/data/bin/symfony
php "%BIN_TARGET%" %*
