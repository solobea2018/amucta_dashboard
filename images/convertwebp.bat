@echo off
setlocal enabledelayedexpansion

REM Loop through all JPG and PNG images
for %%F in (*.jpg *.png *.gif) do (
    set "input=%%F"
    set "output=%%~nF.webp"
    echo Converting !input! to !output!...
    php webp.php "!input!" "!output!"
)

echo Conversion complete!
pause
