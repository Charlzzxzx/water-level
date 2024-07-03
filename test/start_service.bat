@echo off

:: Start the Python application in a new command prompt window
start cmd /k "cd ../ && python api.py"

:: Start the Laravel application in another new command prompt window
start cmd /k "php artisan serve --port 8443"