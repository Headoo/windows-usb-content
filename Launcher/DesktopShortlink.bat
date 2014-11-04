set WORKING_DIRECTORY=%cd%

if NOT EXIST "C:\Users\%USERNAME%\Desktop\headoo.lnk" (
	cscript createLink.vbs "C:\Users\%USERNAME%\Desktop\headoo.lnk" "%WORKING_DIRECTORY%\..\startApp.exe"
)