@echo off
cls
if not exist "%~dp0src\config.php" copy "%~dp0src\config.php.example" "%~dp0src\config.php" >nul
php -S localhost:8000 -t public/
