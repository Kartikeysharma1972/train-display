@echo off
echo ============================================================
echo Installing required packages...
echo ============================================================
pip install -r requirements.txt
echo.
echo ============================================================
echo Starting Jaipur Junction Train Status Display Server...
echo ============================================================
python app.py
