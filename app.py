from flask import Flask, jsonify, request, send_from_directory
import requests
import json
import html

app = Flask(__name__, static_folder='.')

AUTH_TOKEN = "dce6e4e056319e36dac78a98842e5432"
API_URL = "https://enquiry.indianrail.gov.in/ntessrvc/LiveStation"

@app.route('/')
def index():
    return send_from_directory('.', 'index.html')

@app.route('/getTrainData', methods=['POST'])
def get_train_data():
    try:
        post_data = {
            "station": "JP",
            "nextMins": 360
        }
        
        headers = {
            'Content-Type': 'application/json',
            'authToken': AUTH_TOKEN
        }
        
        response = requests.post(
            API_URL,
            json=post_data,
            headers=headers,
            timeout=30,
            verify=False
        )
        
        if response.status_code != 200:
            return jsonify({
                'error': f'API Error: HTTP {response.status_code}'
            }), 500
        
        api_data = response.json()
        
        if not api_data or 'restServiceMessage' not in api_data:
            return jsonify({
                'error': 'Invalid API Response'
            }), 500
        
        if api_data['restServiceMessage']['serviceDataFoundFlag'] != 'Y':
            return jsonify({
                'error': 'No Data Available'
            }), 404
        
        train_list = api_data.get('vTrainList', [])
        
        english_data = []
        hindi_data = []
        
        for train in train_list:
            english_train = {
                'trainNo': train.get('trainNo', ''),
                'trainName': f"{train.get('trainName', '')} ({train.get('srcName', '')} - {train.get('dstnName', '')})",
                'expectedTime': train.get('expectedTime', ''),
                'ADFlag': train.get('ADFlag', ''),
                'platformNo': train.get('platformNo', '-')
            }
            
            train_name_hindi = html.unescape(train.get('trainNameHindi', '')) if train.get('trainNameHindi') else ''
            src_name_hindi = html.unescape(train.get('srcNameHindi', '')) if train.get('srcNameHindi') else ''
            dstn_name_hindi = html.unescape(train.get('dstnNameHindi', '')) if train.get('dstnNameHindi') else ''
            
            hindi_train = {
                'trainNo': train.get('trainNo', ''),
                'trainName': f"{train_name_hindi} ({src_name_hindi} - {dstn_name_hindi})",
                'expectedTime': train.get('expectedTime', ''),
                'ADFlag': train.get('ADFlag', ''),
                'platformNo': train.get('platformNo', '-')
            }
            
            english_data.append(english_train)
            hindi_data.append(hindi_train)
        
        return jsonify({
            'data': {
                'ENG': english_data,
                'HIN': hindi_data
            }
        })
        
    except requests.exceptions.RequestException as e:
        print(f"[ERROR] API Request Failed: {str(e)}")
        import traceback
        traceback.print_exc()
        return jsonify({
            'error': f'Connection Error: {str(e)}'
        }), 500
    except Exception as e:
        print(f"[ERROR] Server Error: {str(e)}")
        import traceback
        traceback.print_exc()
        return jsonify({
            'error': f'Server Error: {str(e)}'
        }), 500

if __name__ == '__main__':
    import urllib3
    urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)
    print("=" * 60)
    print("🚂 Jaipur Junction Train Status Display")
    print("=" * 60)
    print("Server running at: http://localhost:8000")
    print("Press Ctrl+C to stop the server")
    print("=" * 60)
    app.run(host='0.0.0.0', port=8000, debug=True)
