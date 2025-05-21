import cv2

# Load the face detection model
face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')

# Test on a sample image
image = cv2.imread("C:/web/htdocs/web2_01/view/frontoffice/uploads/profiles/6821343227f05_c95add29-3fc2-4c3b-a213-2ea360da264c.JFIF")  # Replace with an actual image
if image is None:
    print("Could not load image. Check the filename and path.")
    exit()
gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
faces = face_cascade.detectMultiScale(gray, 1.1, 4)

print("Faces found:", len(faces))