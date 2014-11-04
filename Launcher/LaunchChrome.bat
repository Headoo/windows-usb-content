@echo off
set WORKING_DIRECTORY=%cd%
tasklist /FI "IMAGENAME eq GoogleChromePortable.exe" | grep GoogleChromePortable.exe

if "%ERRORLEVEL%"=="1" (
	start %WORKING_DIRECTORY%/../../GoogleChromePortable/GoogleChromePortable.exe
)