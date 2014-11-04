@echo off
set WORKING_DIRECTORY=%cd%
tasklist /FI "IMAGENAME eq TeamViewer.exe" | grep TeamViewer.exe

if "%ERRORLEVEL%"=="1" (
	start %WORKING_DIRECTORY%/../../TeamViewer/TeamViewer.exe
)