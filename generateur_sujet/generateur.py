## Générateur de sujet
from random import *
import random
import os.path


def retournerEnTete(cheminFichier) :
    with open(cheminFichier, "r") as fichier:
        texte = fichier.read()
        enTete = texte[0:texte.find('*')]
    return enTete

def retournerReponsesQuestion(question) :
    indiceDebutReponses = min(question.find("+ "), question.find("- "))
    reponses = question[indiceDebutReponses:].split("\n")
    listeReponses = []
    
    indiceDebut = 0

    for i in range(len(reponses)):
        if reponses[i] != "" :
            reponse = (reponses[i][0:]).strip()
            listeReponses.append(reponse)
        
    return listeReponses


def retournerQuestions(cheminFichier) :
    questions = []
    
    with open(cheminFichier, "r") as fichier:
        texte = fichier.read()
        fichierFini = False
        while fichierFini == False:
            indice = texte.find("*", 3)
            
            if indice == -1 :
                question = texte
                fichierFini = True
            else :
                question = texte[0:indice]
                texte = texte[indice:]
            
            indice = question.find("*")
            
            if indice == 0:
                questions.append(question)
        
    return questions


def ecrireNouveauFichier(enTete, questions, cheminFichier) :
    with open(cheminFichier, "w") as fichier:
        fichier.write(enTete)
        
        for i in range(len(questions)) :

            question = questions[i]
            
            ## Si les réponses doivent être mélangées, on 'mélange' la liste des réponses
            orderedQuestion = retournerOrderedQuestion(question)
            
            if orderedQuestion == False :
                
                indiceDebutReponses = min(question.find("+ "), question.find("- "))
                enTeteQuestion = question[0:indiceDebutReponses]
                
                reponses = retournerReponsesQuestion(question)
                random.shuffle(reponses)
                
                fichier.write(enTeteQuestion)

                for j in range(len(reponses)) :
                    
                    fichier.write(reponses[j])
                    fichier.write("\n")
                
            else :
                fichier.write(question)
            
            fichier.write("\n")


def retournerThematiques(question) :
    parametresQuestion = None
    thematiquesQuestion = []
    
    indiceDebutParametre = question.find('{')
    indiceFinParametre = question.find('}')+1
    if indiceDebutParametre != -1 and indiceFinParametre != -1 :
        parametresQuestion = question[indiceDebutParametre:indiceFinParametre]
        if parametresQuestion != None :
            indiceDebutParamThem = question.find('th:')
            indiceFinParamThem = question.find("'", indiceDebutParamThem+5)+1
            paramThem = question[indiceDebutParamThem:indiceFinParamThem]
            indiceDebutThem = paramThem.find("'")+1
            indiceFinThem = paramThem.find("'", 4)
            thematiques = paramThem[indiceDebutThem:indiceFinThem]
            
            fin = False
            while fin == False:
                indice = thematiques.find(",", 3)
                if indice == -1 :
                    thematique = thematiques
                    fin = True
                else :
                    thematique = thematiques[0:indice]
                    thematiques = thematiques[indice+1:len(thematiques)]
                    thematiques = thematiques.strip()                
                thematiquesQuestion.append(thematique)
                
    return thematiquesQuestion


def retournerDifficulte(question) :
    difficulte = ""
    indiceDebutParametre = question.find('{')
    indiceFinParametre = question.find('}')+1
    if indiceDebutParametre != -1 and indiceFinParametre != -1 :
        parametresQuestion = question[indiceDebutParametre:indiceFinParametre]
        if parametresQuestion != None :
            indiceDebutParamDif = question.find('dif:')
            indiceFinParamDif = question.find("'", indiceDebutParamDif+5)+1
            paramDif = question[indiceDebutParamDif:indiceFinParamDif]
            if indiceDebutParamDif != -1 and indiceFinParamDif != -1 :
                indiceDebutDif = paramDif.find("'")+1
                indiceFinDif = paramDif.find("'", 5)
                difficulte = paramDif[indiceDebutDif:indiceFinDif]
            
    return difficulte


def verifierThematiques(thematiques, thematiquesSouhaitees) :
    retour = False
    nbThematiques = len(thematiques)
    for i in range(nbThematiques) :
        if thematiques[i] in thematiquesSouhaitees :
            retour = True
    
    return retour


def verifierDifficulte(difficulte, difficultesSouhaitees) :
    retour = False
    if difficulte in difficultesSouhaitees :
        retour = True
    
    return retour


def saisirDifficultes() :
    finSaisieDifficulte = False
    while finSaisieDifficulte == False :
        print("\n** Saisir les difficultés souhaitées **")
        saisieDifficulte = input("Une à la fois, faire entrer quand terminé : ")
        if saisieDifficulte != "" :
            if saisieDifficulte in difficultesDispo :
                difficultesSouhaitees.append(saisieDifficulte)
            else :
                print("Seules valeurs possibles : l, m, h")
        else :
            finSaisieDifficulte = True
    return difficultesSouhaitees


def saisirThematiques() :
    finSaisieThematique = False
    while finSaisieThematique == False :
        print("\n** Saisir les thématiques souhaitées **")
        saisieThematique = input("Une à la fois, faire entrer quand terminé : ")
        if saisieThematique != "" :
            thematiquesSouhaitees.append(saisieThematique)
        else :
            finSaisieThematique = True
    return thematiquesSouhaitees


def saisirNombreQuestions() :
    finSaisieNombreQuestions = False
    while finSaisieNombreQuestions == False :
        print("\n** Saisir le nombre de questions souhaité **")
        saisieNbQuestions = input("Faire entrer pour valeur par défaut (20) : ")
        if saisieNbQuestions != "" :
            if saisieNbQuestions.isdigit() :
                nbQuestionsSouhaite = saisieNbQuestions
                finSaisieNombreQuestions = True
        else :
            nbQuestionsSouhaite = 20
            finSaisieNombreQuestions = True
    return int(nbQuestionsSouhaite)


def afficherParametresSaisis(thematiques, difficultes, nombreQuestions) :
    print("\n\n** Paramètres saisis : **")
    afficherThematiques(thematiques)
    afficherDifficultes(difficultes)
    afficherNbQuestions(nombreQuestions)


def afficherNbQuestions(nombreQuestions) :
    print("\nNombre de questions : ", end="")
    print(nombreQuestions)
    
    
def afficherDifficultes(difficultes) :
    if len(difficultes) == 0 :
        print("\nAucune difficulté saisie")
    else :
        print("\nDifficultés saisies : ", end="")
        print (', '.join(difficultes))


def afficherThematiques(thematiques) :
    if len(thematiques) == 0 :
        print("\nAucune thématique saisie")
    else :
        print("\nThématiques saisies : ", end="")
        print (', '.join(thematiques))


def saisirFichierQuestions() :
    finSaisieFichier = False
    while finSaisieFichier == False :
        saisieFichier = input("\n** Saisir le nom du fichier contenant les questions ** : ")
        if os.path.isfile(saisieFichier) == False :
            print("Ce fichier n'existe pas")
        else :
            finSaisieFichier = True
    return saisieFichier


def saisirNouveauFichier() :
    finSaisieFichier = False
    while finSaisieFichier == False :
        saisieFichier = input("\n** Saisir le nom du fichier qui sera créé ** : ")
        if saisieFichier.find('.') != -1 :
            print("\nNe pas saisir l'extension")
        else :
            saisieFichier = saisieFichier + ".txt"
            if os.path.isfile(saisieFichier) == True :
                ecraserFichier = input("\nCe fichier existe déjà, voulez-vous l'écraser ? (O/N) :")
                if ecraserFichier == "O" :
                    finSaisieFichier = True
            else :
                finSaisieFichier = True
    
    return saisieFichier


def retournerShuffleQuestions(cheminFichier) :
    shuffle = False
    with open(cheminFichier, encoding='utf-8', mode="r") as fichier:
        texte = fichier.read()
        indice = texte.find("ShuffleQuestions:")
        indice2 = texte.find("\n", indice)
        if indice != -1 :
            texteShuffle = texte[indice+17:indice2].strip()
            if texteShuffle == "1" :
                shuffle = True
    return shuffle

def retournerOrderedQuestion(question) :
    ordered = False
    debut = question.find("ordered")
    if debut != -1 :
        ordered = True
    
    return ordered


## PARAMETRES #######################

difficultesDispo = ['h', 'm', 'l'] ## l: low, m: medium, h: high
difficultesSouhaitees = []
thematiquesSouhaitees = []
nbQuestionsSouhaitees = 4 ## par defaut (saisie: -1), 20

#####################################

#### Saisie des paramètres ##
fichierQuestions = saisirFichierQuestions()
nouveauFichier = saisirNouveauFichier()
thematiquesSouhaitees = saisirThematiques()
difficultesSouhaitees = saisirDifficultes()
nbQuestionsSouhaite = saisirNombreQuestions()
afficherParametresSaisis(thematiquesSouhaitees, difficultesSouhaitees, nbQuestionsSouhaite)
#### Fin saisie des paramètres ##

nvllesQuestions = []
questions = retournerQuestions(fichierQuestions)
enTete = retournerEnTete(fichierQuestions)

while nbQuestionsSouhaite > len(nvllesQuestions) and len(questions) > 0:

    ## Choix aléatoire d'une question
    indiceAleatoire = randrange(0,len(questions))
    questionAleatoire = questions[indiceAleatoire]

    ## Vérification du nombre de réponses
    ## Maximum huit réponses possibles pour éviter les erreurs d'affichage
    reponses = retournerReponsesQuestion(questionAleatoire)
    nbReponsesOk = True
    if len(reponses) > 8 :
        nbReponsesOk = False
    
    ## Vérification des thématiques
    thematiquesQuestionAleatoire = retournerThematiques(questionAleatoire)
    thematiqueOk = False
    if len(thematiquesSouhaitees) == 0 :
        thematiqueOk = True
    else :
        if len(thematiquesQuestionAleatoire) > 0 :
            thematiqueOk = verifierThematiques(thematiquesQuestionAleatoire, thematiquesSouhaitees)
    
    ## Vérification de la difficulté
    difficulteQuestionAleatoire = retournerDifficulte(questionAleatoire)
    difficulteOk = False
    if len(difficultesSouhaitees) == 0 :
        difficulteOk = True
    else :
        if difficulteQuestionAleatoire != "" :
            difficulteOk = verifierDifficulte(difficulteQuestionAleatoire, difficultesSouhaitees)
    
    ## Ajouter la question
    if nbReponsesOk == True and thematiqueOk == True and difficulteOk == True :
        nvllesQuestions.append(questionAleatoire)    

    del(questions[indiceAleatoire])

shuffle = retournerShuffleQuestions(fichierQuestions)
## si les questions doivent être dans un ordre aléatoire, on 'mélange' les questions
if shuffle == True :
    random.shuffle(nvllesQuestions)

## Ecriture du nouveau fichier
ecrireNouveauFichier(enTete, nvllesQuestions, nouveauFichier)

