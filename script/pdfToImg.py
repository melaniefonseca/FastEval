import sys
sujet = sys.argv[1]
cheminImg = sys.argv[2]
#print(sujet)
sys.path.append('c:/users/melan/appdata/local/programs/python/python38/lib/site-packages')
from wand.image import Image
import shutil, os

f = sujet
with(Image(filename=f, resolution=120)) as source:
    for i, image in enumerate(source.sequence):
        newfilename = 'copie_'+str(i + 1) + '.jpeg'
        Image(image).save(filename=newfilename)
        shutil.move('copie_'+str(i + 1) + '.jpeg', cheminImg)

