@echo off
set WORKING_DIRECTORY=%cd%
tasklist /FI "IMAGENAME eq DropboxPortableAHK.exe" | grep DropboxPortableAHK.exe

if "%ERRORLEVEL%"=="1" (
	start %WORKING_DIRECTORY%/../../DropboxPortableAHK/DropboxPortableAHK.exe
)