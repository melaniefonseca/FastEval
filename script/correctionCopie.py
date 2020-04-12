import sys
tabrep = sys.argv[1]
copie = sys.argv[2]
bonne_reponse = sys.argv[3]
mauvaise_reponse = sys.argv[4]
absence_reponse = sys.argv[5]
non_reconnaissance_reponse = sys.argv[6]
cheminEnregistrement = sys.argv[7]
#print(copie)
#print(tabrep)
sys.path.append('c:/users/melan/appdata/local/programs/python/python38/lib/site-packages')
from imutils.perspective import four_point_transform
from imutils import contours
import numpy as np
import argparse
import imutils
import cv2
from PIL import Image
import pytesseract

rep={}
tab=[]
lstrep=[]
nbquest=0
nbrep=0
for i in range(len(tabrep)):
	if tabrep[i] == ',':
		tab.append(int(tabrep[i+1]))
		nbrep+=1
	elif tabrep[i] == ';':
		nbquest+=1
		lstrep.append(tab)
		tab=[]
		nbrep += 1
	elif (tabrep[i-1] != ','):
		tab.append(int(tabrep[i]))
		#rep[i] = tab

for k in range(0, nbquest):
	rep[k] = lstrep[k]

#Définition du corrigé qui mappe le numéro de la question à la bonne réponse
#ANSWER_KEY = {0: [1,2], 1: [2], 2: [2], 3: [0], 4: [1], 5: [1,2], 6: [1], 7: [2], 8: [1,2], 9: [2,1], 10: [2], 11: [1], 12: [0]}
ANSWER_KEY = rep
#Convertion image en niveaux de gris et trouver les contours de l'examen
image = cv2.imread(copie)
gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
blurred = cv2.GaussianBlur(gray, (5, 5), 0)
edged = cv2.Canny(blurred, 75, 200)

cnts = cv2.findContours(edged.copy(), cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
cnts = imutils.grab_contours(cnts)
docCnt = None

if len(cnts) > 0:
	cnts = sorted(cnts, key=cv2.contourArea, reverse=True)
	for c in cnts:
		peri = cv2.arcLength(c, True)
		approx = cv2.approxPolyDP(c, 0.02 * peri, True)
		# Si le contour contient quatre bord alors on à trouvé la zone de la grille de reponse
		if len(approx) == 4:
			docCnt = approx
			break
            
if len(docCnt)>0 :
    paper = four_point_transform(image, docCnt.reshape(4, 2))
    warped = four_point_transform(gray, docCnt.reshape(4, 2))

    thresh = cv2.threshold(warped, 0, 255,
        cv2.THRESH_BINARY_INV | cv2.THRESH_OTSU)[1]

    # recherche contours des case de reponse
    cnts = cv2.findContours(thresh.copy(), cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
    cnts = imutils.grab_contours(cnts)
    questionCnts = []

    previousY=0
    counter=0
    previousCounter=0
    tabNbQuestions=[]
    # boucle sur le contours trouvé
    for c in cnts:
        (x, y, w, h) = cv2.boundingRect(c)
        ar = w / float(h)
        if w >= 20 and h >= 20 and ar >= 0.9 and ar <= 1.1:
            if previousY==0 or (previousY-20 <= y <= previousY+20):
                counter+=1
            else:
                if previousCounter==0 or previousCounter!=counter:
                    tabNbQuestions.append(counter)
                previousCounter=counter
                counter=1
            previousY = y
            questionCnts.append(c)

    # trie les contours de haut en bas
    questionCnts = contours.sort_contours(questionCnts,method="top-to-bottom")[0]
    correct = 0
    # chaque question à plusieurs réponses possibles
    for r in range(0, len(tabNbQuestions)):
        for (q, i) in enumerate(np.arange(0, len(questionCnts), tabNbQuestions[r])):
            #trie les contours de la question actuelle de gauche à droite
            cnts = contours.sort_contours(questionCnts[i:i + tabNbQuestions[r]])[0]
            # initialisation de la réponse
            #bubbled = None
            bubbled = []

            # initilaisation de la couleur du contour et l'indice de la bonne réponse
            color = (0, 0, 255)
            k = ANSWER_KEY[q]

            for i in range(1, len(k)+1):
                bubbled.insert(i-1, None)
                #boucle sur les contours trié
                for (j, c) in enumerate(cnts):
                    mask = np.zeros(thresh.shape, dtype="uint8")
                    cv2.drawContours(mask, [c], -1, 255, -1)

                    # compte le nombre de pixels non nuls dans la zone de la bulle
                    mask = cv2.bitwise_and(thresh, thresh, mask=mask)
                    total = cv2.countNonZero(mask)

                    find=0
                    if len(bubbled)>1:
                        for s in range(1,len(bubbled)):
                            if bubbled[s-1] is not None and bubbled[s-1][1]==j:
                                find=1

                    # on conserve la réponse qui contient le plus de pixel non nul
                    if (bubbled[i-1] is None or total > bubbled[i-1][0]) and find==0:
                        del bubbled[i-1]
                        bubbled.insert(i-1, (total, j))
                        #bubbled[i] = (total, j)

            #print(bubbled[:])

            # verifie si la réponse cocher est correcte
            # Application du barème
            for b in range(1, len(bubbled)+1):
                # reinitilaisation de la couleur du contour
                color = (0, 0, 255)
                bubbled.sort()
                if k[b-1] == bubbled[b-1][1] and bubbled[b-1][0]>600:
                    #changement de la couleur du contour si la réponse est correcte
                    color = (0, 255, 0)
                    correct += int(bonne_reponse)
                elif bubbled[b-1][0]<600:
                    correct += int(absence_reponse)
                elif k[b-1] != bubbled[b-1][1]:
                    correct += int(mauvaise_reponse)
                else :
                    correct += int(non_reconnaissance_reponse);

                # dessine le contour de la bonne réponse sur le test
                cv2.drawContours(paper, [cnts[k[b-1]]], -1, color, 3)


    # calcul du score
    correct=(correct/nbrep)*nbquest
    score = str(correct.__round__ (2))+"/"+str(nbquest)
    # affichage du score sur la feuille
    print(score)
    cv2.putText(paper, score, (10, 30),cv2.FONT_HERSHEY_SIMPLEX, 0.9, (0, 0, 255), 2)
    #cv2.imshow("Original", image)
    #cv2.imshow("Exam", paper)
    cv2.imwrite(cheminEnregistrement,paper)
    #image1 = Image.open(r'C:\wamp64\www\FastEval\script\result.jpg')
    #im1 = image1.convert('RGB')
    # convertion du résultat en pdf
    #im1.save(r'C:\wamp64\www\FastEval\script\ExamenCorrige.pdf')
    cv2.waitKey(0)
else :
	print(-1)