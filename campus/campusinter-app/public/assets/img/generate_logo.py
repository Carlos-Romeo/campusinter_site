#!/usr/bin/env python3
"""Generate Campus Inter logo using Gemini API via urllib (no pip needed)"""

import os
import json
import base64
import urllib.request
import urllib.error

API_KEY = os.environ.get("GEMINI_API_KEY", "")
MODEL = "gemini-3.1-flash-image"
OUTPUT_DIR = os.path.dirname(os.path.abspath(__file__))

PROMPT = """Generate a professional logo for "Campus Inter" — an African higher education platform.

Design requirements:
- Modern, clean, minimalist logo
- Green primary color (#159447) with dark green (#0F7A3A) accent
- Icon: abstract graduation cap merged with a network/connection symbol (representing inter-campus connectivity)
- Simple geometric shapes, scalable vector style
- Centered on pure white background
- No text in the logo icon (just the symbol)
- Square format, perfectly centered
- High contrast, clear edges
- Professional quality suitable for education/tech brand"""

def generate():
    url = f"https://generativelanguage.googleapis.com/v1beta/models/{MODEL}:generateContent?key={API_KEY}"
    
    payload = {
        "contents": [{"parts": [{"text": PROMPT}]}],
        "generationConfig": {
            "responseModalities": ["TEXT", "IMAGE"],
            "temperature": 1.0,
        }
    }
    
    data = json.dumps(payload).encode("utf-8")
    req = urllib.request.Request(url, data=data, headers={"Content-Type": "application/json"})
    
    print("Generating logo with Gemini...")
    try:
        with urllib.request.urlopen(req, timeout=120) as resp:
            result = json.loads(resp.read().decode("utf-8"))
    except urllib.error.HTTPError as e:
        body = e.read().decode("utf-8", errors="replace")
        print(f"API Error {e.code}: {body[:500]}")
        return None
    
    # Extract image from response
    candidates = result.get("candidates", [])
    if not candidates:
        print("No candidates in response")
        print(json.dumps(result, indent=2)[:1000])
        return None
    
    parts = candidates[0].get("content", {}).get("parts", [])
    for part in parts:
        if "inlineData" in part:
            img_data = part["inlineData"]
            mime = img_data.get("mimeType", "image/png")
            ext = "png" if "png" in mime else "jpg" if "jpeg" in mime or "jpg" in mime else "webp"
            b64 = img_data["data"]
            img_bytes = base64.b64decode(b64)
            
            ts = __import__("datetime").datetime.now().strftime("%Y%m%d_%H%M%S")
            filename = f"campus_inter_logo_{ts}.{ext}"
            filepath = os.path.join(OUTPUT_DIR, filename)
            with open(filepath, "wb") as f:
                f.write(img_bytes)
            print(f"Logo saved: {filepath} ({len(img_bytes)} bytes)")
            return filepath
    
    # If only text was returned
    for part in parts:
        if "text" in part:
            print(f"Text response: {part['text'][:300]}")
    
    print("No image found in response")
    return None

if __name__ == "__main__":
    if not API_KEY:
        print("Error: GEMINI_API_KEY not set")
    else:
        generate()
