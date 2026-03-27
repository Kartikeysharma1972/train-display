# Jaipur Junction - Live Train Status Display

A real-time train status display system for Jaipur Junction using Indian Railways NTES API.

## Features

- ✅ Live train data from NTES API (6-hour window)
- ✅ Auto-refresh every 10 seconds
- ✅ Bilingual display (English/Hindi alternating)
- ✅ Modern, responsive UI with gradient design
- ✅ Large, clear fonts for visibility
- ✅ Color-coded arrival/departure indicators
- ✅ Platform number highlighting

## Display Columns

1. **TR NO.** - Train Number
2. **TRAIN NAME** - Train Name (Source - Destination)
3. **EXP. TIME** - Expected Arrival/Departure Time
4. **A/D** - Arrival/Departure Flag
5. **PF.** - Platform Number

## Requirements

- PHP 7.4 or higher
- cURL extension enabled
- Web server (Apache/Nginx)

## Setup Instructions

1. **Copy files to web server directory**
   ```
   train-display/
   ├── index.html
   ├── getTrainData.php
   └── README.md
   ```

2. **Ensure PHP cURL is enabled**
   - Check `php.ini` for `extension=curl`

3. **Configure station (if needed)**
   - Edit `getTrainData.php`
   - Change station code: `"station" => "JP"` (JP = Jaipur)
   - Change time window: `"nextMins" => 360` (360 mins = 6 hours)

4. **Access the application**
   - Open `http://localhost/train-display/index.html` in browser

## API Configuration

- **API Endpoint**: `https://enquiry.indianrail.gov.in/ntessrvc/LiveStation`
- **Auth Token**: `dce6e4e056319e36dac78a98842e5432`
- **Station Code**: JP (Jaipur Junction)
- **Time Window**: 360 minutes (6 hours)

## How It Works

1. Frontend (`index.html`) sends AJAX request every 10 seconds
2. Backend (`getTrainData.php`) fetches data from NTES API 951
3. Data is processed and formatted for both English and Hindi
4. Display alternates between languages every 10 seconds
5. UI updates with smooth transitions

## Color Scheme

- **Header**: Purple gradient
- **Table Header**: Green gradient
- **Arrival Flag**: Green gradient
- **Departure Flag**: Red gradient
- **Platform**: Purple gradient
- **Expected Time**: Orange/Gold

## Browser Compatibility

- Chrome/Edge (Recommended)
- Firefox
- Safari
- Opera

## Troubleshooting

**No data showing:**
- Check PHP error logs
- Verify cURL is enabled
- Check auth token validity
- Ensure internet connectivity

**API errors:**
- Verify NTES API is accessible
- Check auth token
- Review station code

**Display issues:**
- Clear browser cache
- Check console for JavaScript errors
- Verify jQuery is loading

## License

For authorized railway use only. Data provided by CRIS (Centre for Railway Information Systems).
