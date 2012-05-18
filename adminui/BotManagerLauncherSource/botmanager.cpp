// botmanager.cpp : Defines the entry point for the application.
//


#define WIN32_LEAN_AND_MEAN             // Exclude rarely-used stuff from Windows headers

#include "targetver.h"
#include "resource.h"

#include <windows.h>
#include <tchar.h>
#include <malloc.h>

int APIENTRY _tWinMain(HINSTANCE hInstance,
                     HINSTANCE hPrevInstance,
                     LPTSTR    lpCmdLine,
                     int       nCmdShow) {
    STARTUPINFO si;
    PROCESS_INFORMATION pi;

    ZeroMemory(&si, sizeof(si));
    si.cb = sizeof(si);
    ZeroMemory(&pi, sizeof(pi));

    // start bot manager
    LPTSTR commandLine = _tcsdup(TEXT("win32\\php -c php-win.ini -f adminui/adminui.php"));
    CreateProcess(NULL,             // No module name (use command line)
                  commandLine,      // Command line
                  NULL,             // Process handle not inheritable
                  NULL,             // Thread handle not inheritable
                  FALSE,            // Set handle inheritance to FALSE
                  CREATE_NO_WINDOW, // creation flags
                  NULL,             // Use parent's environment block
                  NULL,             // Use parent's starting directory 
                  &si,              // Pointer to STARTUPINFO structure
                  &pi);             // Pointer to PROCESS_INFORMATION structure

    // Close process and thread handles. 
    free(commandLine);
    CloseHandle(pi.hProcess);
    CloseHandle(pi.hThread);

    return 0;
}
