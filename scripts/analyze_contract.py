import os
import sys
import pytesseract
import cv2
import PyPDF2
import json

# Set Tesseract path based on OS
if os.name == 'nt':  # Windows
    pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'
else:  # Linux
    pytesseract.pytesseract.tesseract_cmd = '/usr/bin/tesseract'

def analyze_file(file_path):
    # Check if it's a PDF or image
    if file_path.endswith('.pdf'):
        with open(file_path, 'rb') as f:
            pdf = PyPDF2.PdfReader(f)  # Changed from PdfFileReader to PdfReader
            text = pdf.pages[0].extract_text()  # Updated method call
    else:
        img = cv2.imread(file_path)
        text = pytesseract.image_to_string(img)

    # Simple fraud check: look for expected text (customize this)
    expected_terms = ["Bus Transportation Agreement", "Vendor Signature", "LTO", "Land Transportation Office"]
    is_fraud = not any(term in text for term in expected_terms)

    # Image tampering check (for images only)
    notes = ""
    if not file_path.endswith('.pdf'):
        gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
        blur = cv2.Laplacian(gray, cv2.CV_64F).var()
        if blur < 100:  # Low variance might indicate tampering
            is_fraud = True
            notes = "Possible image tampering detected (low clarity)."

    return {"is_fraud": is_fraud, "notes": notes}

if __name__ == "__main__":
    file_path = sys.argv[1]
    result = analyze_file(file_path)
    print(json.dumps(result))
