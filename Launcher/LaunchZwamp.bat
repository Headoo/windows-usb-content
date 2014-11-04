@echo off
set WORKING_DIRECTORY=%cd%
tasklist /FI "IMAGENAME eq zwamp.exe" | grep zwamp.exe

if "%ERRORLEVEL%"=="1" (
	start %WORKING_DIRECTORY%/../../zwamp.exe
)

