import random
import os.path
import sys

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


def retournerReponsesQuestion(question) :
    indiceDebutReponses = min(question.find("+ "), question.find("- "))
    reponses = question[indiceDebutReponses:].split("\n")
    listeReponses = []
    
    indiceDebut = 0

    for i in range(len(reponses)):
        if reponses[i] != "" :
            reponse = (reponses[i]).strip()
            listeReponses.append(reponse)
        
    return listeReponses


def estBonneReponse(reponse) :
    bonneReponse = False
    if reponse[0:1] == "+" :
        bonneReponse = True

    return bonneReponse

## Saisie des paramètres ##################################

fichierSujet = sys.argv[1]

###########################################################

## Liste contenant toutes les questions
questions = retournerQuestions(fichierSujet)

listeReponsesSujet = ""

## Parcours des questions une à une
for i in range(len(questions)) :
    
    ## Liste contenant toutes les réponses de la question
    reponses = retournerReponsesQuestion(questions[i])

    ## Parcours des réponses de la question une à une
    for j in range(len(reponses)) :
        if estBonneReponse(reponses[j]) == True :
            listeReponsesSujet += str(j) + ","
    listeReponsesSujet = listeReponsesSujet[:-1]
    listeReponsesSujet += ";"

print(listeReponsesSujet)
        










