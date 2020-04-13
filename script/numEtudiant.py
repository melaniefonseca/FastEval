import sys
copie = sys.argv[1]
cheminImgNum = sys.argv[2]
sys.path.append('c:/users/melan/appdata/local/programs/python/python38/lib/site-packages')
import numpy as np
import cv2
from PIL import Image

def find_counts(contours):
    result = []
    dictionary = {}
    for c in contours:
        (x, y, w, h) = cv2.boundingRect(c)
        minus = abs(w - h)
        concat = str(w)+","+str(h)
        if concat not in dictionary:
            dictionary[concat] = 1
        else:
            dictionary[concat] = dictionary[concat] + 1
        if w >= 25 and h >= 25 and minus <= 2:
            result.append(c)
    return result, dictionary


#img = cv2.imread('resources/test/warped1.jpg')

img2 = Image.open(copie)
img_size = img2.size
left = img_size[0]/2 - 30
top = 0
width = img_size[0]/2 +30
height = img_size[1]/2
box = (left, top, left+width, top+height)
area = img2.crop(box)
#area.show()
area.save(cheminImgNum, "PNG")
img = cv2.imread(cheminImgNum)

imgray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
ret, thresh = cv2.threshold(imgray, 127, 255, 0)
contours, hierarchy = cv2.findContours(thresh, cv2.RETR_TREE, cv2.CHAIN_APPROX_NONE)
question_contours, dictionary = find_counts(contours)

question_contours = list(reversed(question_contours))
cv2.drawContours(img, question_contours, -1, (0, 255, 0), 2)

anonymat_num = ""
i = 0
num = "0"
min = -1
compteur = 0
for circle in question_contours:
    mask = np.zeros(thresh.shape, dtype="uint8")
    cv2.drawContours(mask, [circle], -1, 255, -1)
    mask = cv2.bitwise_and(thresh, thresh, mask=mask)
    actual = cv2.countNonZero(mask)
    if min == -1 or actual < min:
        min = actual
        num = str(i)
    if i == 9:
        anonymat_num = anonymat_num + num
        num = "0"
        min = -1
        i = -1
    i = i + 1

print(anonymat_num)

#cv2.imwrite('./resources/test/warped1_colored.jpg', img)
cv2.waitKey(0)
cv2.destroyAllWindows()