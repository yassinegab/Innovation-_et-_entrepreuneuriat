import cv2
import sys

def detect_face(image_path):
    try:
        # Load the pre-trained Haar Cascade classifier
        face_cascade = cv2.CascadeClassifier(
            cv2.data.haarcascades + 'haarcascade_frontalface_default.xml'
        )
        
        # Read the image
        image = cv2.imread(image_path)
        if image is None:
            print("False")  # Image not found/corrupt
            return False
        
        # Convert to grayscale (required for face detection)
        gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
        
        # Detect faces
        faces = face_cascade.detectMultiScale(
            gray, 
            scaleFactor=1.1,  # Adjust for better detection
            minNeighbors=4,   # Higher = fewer false positives
            minSize=(30, 30)  # Minimum face size
        )
        
        # Return True if at least one face is detected
        print("True" if len(faces) > 0 else "False")
        return len(faces) > 0
        
    except Exception as e:
        print(f"Error: {e}", file=sys.stderr)
        print("False")
        return False

if __name__ == "__main__":
    if len(sys.argv) != 2:
        print("Usage: python facedetect.py <image_path>")
        sys.exit(1)
    detect_face(sys.argv[1])