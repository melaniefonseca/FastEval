from reportlab.pdfgen import canvas
from reportlab.lib import utils
from reportlab.lib.colors import black, white
from textwrap import wrap
import random
import os.path
from reportlab.lib.utils import simpleSplit
import sys


def retournerTitre(cheminFichier) :
    titre = "Evaluation"
    with open(cheminFichier, encoding='utf-8', mode="r") as fichier:
        texte = fichier.read()
        indice = texte.find("Title:")
        indice2 = texte.find("\n", indice)
        if indice != -1 :
            titre = texte[indice+6:indice2].strip()
    return titre


def retournerPresentation(cheminFichier) :
    presentation = ""
    with open(cheminFichier, encoding='utf-8', mode="r") as fichier:
        texte = fichier.read()
        indice = texte.find("Presentation:")
        indice2 = texte.find("\n", indice)
        if indice != -1 :
            presentation = texte[indice+13:indice2].strip()
    return presentation


def retournerColonnes(cheminFichier) :
    colonnes = 1
    with open(cheminFichier, encoding='utf-8', mode="r") as fichier:
        texte = fichier.read()
        indice = texte.find("Columns:")
        indice2 = texte.find("\n", indice)
        if indice != -1 :
            texteColonnes = texte[indice+8:indice2].strip()
            texteColonnes = int(texteColonnes)
            if texteColonnes > 1 :
                colonnes = 2 # bloqué à 2 pour ne pas avoir de problème d'affichage trop condensé
    return colonnes


def retournerQuestions(cheminFichier) :
    questions = []
    
    with open(cheminFichier, encoding='utf-8', mode="r") as fichier:
        texte = fichier.read()
        fichierFini = False
        while fichierFini == False:
            indice = texte.find("*", 3)
            if indice == -1 :
                question = texte
                fichierFini = True
            else :
                question = texte[:indice]
                texte = texte[indice:]
            
            indice = question.find("*")
            
            if indice == 0:
                questions.append(question)
        
    return questions


def retournerIntituleQuestion(question) :
    debutVerbatim = question.find("[verbatim]")
    debutImage = question.find("![height")
    
    if debutVerbatim != -1 or debutImage != -1 :
        if debutVerbatim != -1 and debutImage != -1 :
            debutVerbatim = min(debutVerbatim, debutImage)
        elif debutImage != -1 :
            debutVerbatim = debutImage
        indiceDebut = max(question[0:debutVerbatim].find("}"), question[0:debutVerbatim].find("]"), 0)+1
        indiceFin = debutVerbatim
    else :
        indiceDebut = max(question.find("}"), question.find("]"), 0)+1
        indiceFin = min(question.find("+ ", indiceDebut), question.find("- ", indiceDebut))
    
    if question[0] == "*" :
        indiceDebut = indiceDebut+1
    intitule = " ".join(question[indiceDebut:indiceFin].split())
    
    return intitule


def retournerVerbatimQuestion(question) :
    verbatim = ""
    debutVerbatim = question.find("[verbatim]")
    finVerbatim = question.find("[/verbatim]")

    if debutVerbatim != -1 :
        verbatim = question[debutVerbatim+10:finVerbatim].strip()
    
    return verbatim


def retournerUrlImageQuestion(question) :
    urlImage = ""
    debut = question.find("![height")
    debutImage = question.find("]", debut)
    finImage = question.find("!", debutImage)

    if debutImage != -1 :
        urlImage = question[debutImage+1:finImage].strip()
        urlImage = os.getcwd().replace("\\","/")+"/"+urlImage
    
    return urlImage


def retournerTailleImageQuestion(question) :
    tailleImage = ""
    debutImage = question.find("![height=")
    finImage = question.find("cm", debutImage)
    
    if debutImage != -1 :
        tailleImage = int(question[debutImage+9:finImage].strip())
    
    return tailleImage


def retournerColonnesQuestion(question) :
    colonnes = 1
    debut = question.find("columns")
    debut = question.find("=", debut)
    fin = min(question.find("]", debut), question.find(",", debut))
    if debut != -1 :
        texteColonnes = question[debut+1:fin].strip()
        texteColonnes = int(texteColonnes)
        if texteColonnes > 4 :
            colonnes = 4 # bloqué à 4 pour ne pas avoir de problème d'affichage trop condensé
    
    return colonnes


def retournerHorizQuestion(question) :
    horiz = False
    debut = question.find("horiz")
    if debut != -1 :
        horiz = True
    
    return horiz


def retournerReponsesQuestion(question) :
    indiceDebutReponses = min(question.find("+ "), question.find("- "))
    reponses = question[indiceDebutReponses:].split("\n")
    listeReponses = []
    
    indiceDebut = 0

    for i in range(len(reponses)):
        if reponses[i] != "" :
            reponse = (reponses[i][1:]).strip()
            listeReponses.append(reponse)
        
    return listeReponses


def getNbRepMax(questions) :
    nb = 0
    for i in range(len(questions)):
        reponses = retournerReponsesQuestion(questions[i])
        if len(reponses) > nb :
            nb = len(reponses)
    
    return nb
    

def retournerCode(cheminFichier) :
    code = 8
    with open(cheminFichier, encoding='utf-8', mode="r") as fichier:
        texte = fichier.read()
        indice = texte.find("Code:")
        indice2 = texte.find("\n", indice)
        if indice != -1 :
            code = int(texte[indice+5:indice2].strip())
            if code > 10 :
                code = 10
    return code


###########################################################

liste = ["a","b","c","d","e","f", "g", "h", "i", "j", "k"]

## Saisie des paramètres ##################################

fichierQuestions = sys.argv[1]
nouveauFichier = sys.argv[2]
if sys.argv[3] == "false":
    anonymat = False
else:
    anonymat = True
numSujet = sys.argv[4]


###########################################################

canvas = canvas.Canvas(nouveauFichier)
titre = retournerTitre(fichierQuestions)

presentation = retournerPresentation(fichierQuestions)
colonnes = retournerColonnes(fichierQuestions)

## creation d'une liste contenant toutes les questions
questions = retournerQuestions(fichierQuestions)

## Feuilles des questions ######
canvas.setFont('Helvetica-Bold', 12)
canvas.drawCentredString(300, 710, titre)

canvas.setFont('Helvetica', 12)

canvas.drawString(500, 770, "Sujet "+numSujet)

## Etudiant nominatif
if anonymat == False :
    canvas.drawString(50, 750, "Nom : ____________")
    canvas.drawString(50, 730, "Prénom : ____________")
## Etudiant anonyme
else : 
    canvas.drawString(50, 750, "Numéro d'anonymat : ____________")

## Consignes
if presentation != "" :
    canvas.drawString(50, 680, presentation)

textObject=canvas.beginText(50,640)

indiceColonne = 1


## Parcours des questions une à une
for i in range(len(questions)) :
    
    nbLignes = 0

    ## Gestion du changement de colonne et/ou de page
    if textObject.getY() < 250 :
        if colonnes == 2 :
            indiceColonne = indiceColonne + 1
            if indiceColonne == 2 :
                canvas.drawText(textObject)
                textObject=canvas.beginText(320,640)
            else :
                indiceColonne = 1
                canvas.drawText(textObject)
                textObject=canvas.beginText(50,640)
        else :
            canvas.drawText(textObject)

            canvas.drawCentredString(300, 50, str(canvas.getPageNumber()))
            
            canvas.showPage()
            canvas.setFont('Helvetica', 12)
            canvas.drawString(500, 770, "Sujet "+numSujet)
            nbLignes = 0
            textObject=canvas.beginText(50,680)
            if anonymat == False :
                canvas.drawString(50, 750, "Nom : ____________")
                canvas.drawString(50, 730, "Prénom : ____________")
            else : 
                canvas.drawString(50, 750, "Numéro d'anonymat : ____________")
    
    intitule = ""+str(i+1)+". "+retournerIntituleQuestion(questions[i])
    
    if colonnes == 2 :
        intitule = "\n".join(wrap(intitule, 45))
    else :
        intitule = "\n".join(wrap(intitule, 90))
    
    textObject.textLines(intitule)

    nbLignes = nbLignes + len(simpleSplit(intitule, 'Helvetica', 12, 500))
    
    verbatim = retournerVerbatimQuestion(questions[i])
    if verbatim != "":
        intitule = verbatim
        textObject.setFont('Helvetica-Oblique', 12)
        textObject.textLines(intitule)
        textObject.setFont('Helvetica', 12)
    
    urlImage = retournerUrlImageQuestion(questions[i])
    if urlImage != "" :
        tailleImage = retournerTailleImageQuestion(questions[i])    
        img = utils.ImageReader(urlImage)
        w, h = img.getSize()
        ratio = w / float(h)
        hauteurImage = tailleImage*35
        largeurImage = hauteurImage*ratio
        
        textObject.textLine(" ")
        
        canvas.drawImage(urlImage,textObject.getX(),textObject.getY()-(nbLignes*50),width=largeurImage,height=hauteurImage,mask='auto') 
        
        textObject.textLine(" ")
        textObject.textLine(" ")
        textObject.textLine(" ")
        textObject.textLine(" ")
        textObject.textLine(" ")
        
    horizQuestion = retournerHorizQuestion(questions[i])
    columnsQuestion = retournerColonnesQuestion(questions[i])
    
    reponses = retournerReponsesQuestion(questions[i])
    
    decalage = 0
    indiceColonneReponse = 1

    ## Affichage des réponses
    for j in range(len(reponses)) :
        x = textObject.getX()
        y = textObject.getY()
        textObject.moveCursor(15, 0)
        if colonnes == 2 :
            intitule = "\n".join(wrap(reponses[j], 45))
        else :
            intitule = "\n".join(wrap(reponses[j], 90))
        
        if horizQuestion == True :
            longueurIntitule = canvas.stringWidth(intitule, 'Helvetica', 12)
            textObject.textOut(liste[j]+" : "+intitule)
            textObject.moveCursor(longueurIntitule+30, 0)
            decalage = decalage + longueurIntitule+30
        else :
            textObject.textLines(liste[j]+" : "+intitule)

        nbLignes = nbLignes + len(simpleSplit(intitule, 'Helvetica', 12, 500))
        textObject.moveCursor(-15, 0)
    
    if decalage != 0 :
        textObject.moveCursor(-(decalage), 0)
        textObject.textLine("")
    
    textObject.textLine("")

canvas.drawCentredString(300, 50, str(canvas.getPageNumber()))
canvas.drawText(textObject)

## Feuilles des réponses

canvas.showPage()
canvas.setFont('Helvetica', 12)
textObject=canvas.beginText(50,760)
textObject.setLeading(5)

canvas.setFont('Helvetica-Bold', 12)
canvas.drawCentredString(300, 770, "Feuille de réponses")
canvas.setFont('Helvetica', 12)
canvas.drawString(500, 770, "Sujet "+numSujet)

code = retournerCode(fichierQuestions)

## Gestion de la grille de remplissage du numéro étudiant / anonymat
debutY = 730
espaceY = 20
finX = 540
                
for i in range(code) :
    if i == code - 1 :
        debutX = finX-i*16
        
    canvas.rect(finX-7-i*16.1, debutY, 14, 18)
    canvas.circle(finX-i*16, debutY-espaceY*1, 6, stroke=1, fill=0)
    canvas.circle(finX-i*16, debutY-espaceY*2, 6, stroke=1, fill=0)
    canvas.circle(finX-i*16, debutY-espaceY*3, 6, stroke=1, fill=0)
    canvas.circle(finX-i*16, debutY-espaceY*4, 6, stroke=1, fill=0)
    canvas.circle(finX-i*16, debutY-espaceY*5, 6, stroke=1, fill=0)
    canvas.circle(finX-i*16, debutY-espaceY*6, 6, stroke=1, fill=0)
    canvas.circle(finX-i*16, debutY-espaceY*7, 6, stroke=1, fill=0)
    canvas.circle(finX-i*16, debutY-espaceY*8, 6, stroke=1, fill=0)
    canvas.circle(finX-i*16, debutY-espaceY*9, 6, stroke=1, fill=0)
    canvas.circle(finX-i*16, debutY-espaceY*10, 6, stroke=1, fill=0)

if anonymat == True :
    canvas.drawString(debutX-100, debutY+4, "N° anonymat :")
else :
    canvas.drawString(350, debutY-espaceY*12, "Nom : ____________")
    canvas.drawString(350, debutY-espaceY*13, "Prénom : ____________")
    canvas.drawString(debutX-90, debutY+4, "N° étudiant :")

debutY = debutY - 5

canvas.drawString(debutX-22, debutY-espaceY*1, "0")
canvas.drawString(debutX-22, debutY-espaceY*2, "1")
canvas.drawString(debutX-22, debutY-espaceY*3, "2")
canvas.drawString(debutX-22, debutY-espaceY*4, "3")
canvas.drawString(debutX-22, debutY-espaceY*5, "4")
canvas.drawString(debutX-22, debutY-espaceY*6, "5")
canvas.drawString(debutX-22, debutY-espaceY*7, "6")
canvas.drawString(debutX-22, debutY-espaceY*8, "7")
canvas.drawString(debutX-22, debutY-espaceY*9, "8")
canvas.drawString(debutX-22, debutY-espaceY*10, "9")

if anonymat == False :
    canvas.rect(debutX-100, debutY-espaceY*11+6, code*16+100, 245)
else :
    canvas.rect(debutX-110, debutY-espaceY*11+6, code*16+110, 245)


## Gestion des réponses

nombreReponsesMax = getNbRepMax(questions)
    
xi = 40
xj = 0
yi = 745
yj = 0

t=0

for i in range(len(questions)) :
    
    numQuestion = ""+str(i+1)+". "
    
    if textObject.getY() > 1200 :
        canvas.drawText(textObject)
        yj = 725-45*t+20
        canvas.rect(xi, 100, xj-xi, 640)
        xj = 0
        yj = 0
        canvas.drawCentredString(300, 50, str(canvas.getPageNumber()))                
        canvas.showPage()
        canvas.setFont('Helvetica', 12)
        textObject=canvas.beginText(50,760)
        textObject.setLeading(5)
        canvas.setFont('Helvetica-Bold', 12)
        canvas.drawCentredString(300, 770, "Feuille de réponses")
        canvas.setFont('Helvetica', 12)
        canvas.drawString(500, 770, "Sujet "+numSujet)
        
        
        ## Gestion de la grille de remplissage du numéro étudiant / anonymat
        debutY = 730
        espaceY = 20
        finX = 540
                
        for i in range(code) :
            if i == code - 1 :
                debutX = finX-i*16
        
            canvas.rect(finX-7-i*16.1, debutY, 14, 18)
            canvas.circle(finX-i*16, debutY-espaceY*1, 6, stroke=1, fill=0)
            canvas.circle(finX-i*16, debutY-espaceY*2, 6, stroke=1, fill=0)
            canvas.circle(finX-i*16, debutY-espaceY*3, 6, stroke=1, fill=0)
            canvas.circle(finX-i*16, debutY-espaceY*4, 6, stroke=1, fill=0)
            canvas.circle(finX-i*16, debutY-espaceY*5, 6, stroke=1, fill=0)
            canvas.circle(finX-i*16, debutY-espaceY*6, 6, stroke=1, fill=0)
            canvas.circle(finX-i*16, debutY-espaceY*7, 6, stroke=1, fill=0)
            canvas.circle(finX-i*16, debutY-espaceY*8, 6, stroke=1, fill=0)
            canvas.circle(finX-i*16, debutY-espaceY*9, 6, stroke=1, fill=0)
            canvas.circle(finX-i*16, debutY-espaceY*10, 6, stroke=1, fill=0)

        if anonymat == True :
            canvas.drawString(debutX-100, debutY+4, "N° anonymat :")
        else :
            canvas.drawString(350, 775, "Nom : ____________")
            canvas.drawString(350, 755, "Prénom : ____________")
            canvas.drawString(debutX-90, debutY+4, "N° étudiant :")

        debutY = debutY - 5

        canvas.drawString(debutX-22, debutY-espaceY*1, "0")
        canvas.drawString(debutX-22, debutY-espaceY*2, "1")
        canvas.drawString(debutX-22, debutY-espaceY*3, "2")
        canvas.drawString(debutX-22, debutY-espaceY*4, "3")
        canvas.drawString(debutX-22, debutY-espaceY*5, "4")
        canvas.drawString(debutX-22, debutY-espaceY*6, "5")
        canvas.drawString(debutX-22, debutY-espaceY*7, "6")
        canvas.drawString(debutX-22, debutY-espaceY*8, "7")
        canvas.drawString(debutX-22, debutY-espaceY*9, "8")
        canvas.drawString(debutX-22, debutY-espaceY*10, "9")

        if anonymat == False :
            canvas.rect(debutX-100, debutY-espaceY*11+6, code*16+100, 245)
        else :
            canvas.rect(debutX-110, debutY-espaceY*11+6, code*16+110, 245)

        t = 0
    
    textObject.moveCursor(0, 40)
    textObject.textLines(numQuestion)
    
    reponses = retournerReponsesQuestion(questions[i])
    
    y = 725-45*t
    
    if i == len(questions)-1 :
        if 725-20-45*t > yj :
            yj = y-30
    
    for j in range(nombreReponsesMax) :
        if j < len(reponses) :
            
            if j == len(reponses)-1 :
                if 80+30+30*j > xj :
                    xj = 80+20+30*j
            
            textObject.moveCursor(30,0)
            x = textObject.getX()
            
            canvas.setFont('Helvetica', 10)
            canvas.drawString(x-3, y-3, liste[j])
                    
        canvas.setStrokeColor(black)
        canvas.circle(80+30*j, y, 9, stroke=1, fill=0)

    xj = 80+20+30*(nombreReponsesMax-1)
    textObject.moveCursor(-30*len(reponses), 0)
    t = t+1

canvas.rect(xi, 100, xj-xi, 640)
canvas.drawCentredString(300, 50, str(canvas.getPageNumber()))
canvas.drawText(textObject)


canvas.save()



