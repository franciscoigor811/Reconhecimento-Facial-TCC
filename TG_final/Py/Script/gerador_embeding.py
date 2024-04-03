from PIL import Image
from os import listdir
from os.path import isdir
from numpy import asarray
import tensorflow as tf
#from tensorflow import
from keras.models import load_model
def load_face(filename):
    image = Image.open(filename)
    image = image.convert("RGB")
    return asarray(image)

def load_faces(directory_src):
    faces = []  # Inicialize a lista 'faces' aqui
    for filename in listdir(directory_src):
        path = directory_src + filename
        try:
            faces.append(load_face(path))
        except Exception as e:  # Capture exceções para relatar erros
            print("Erro na imagem {}: {}".format(path, str(e)))
    return faces

def load_fotos(directory_src):
    x, y = list(), list()

    for subdir in listdir(directory_src):
        path = directory_src + subdir + '\\'

        if not isdir(path):
            continue

        faces = load_faces(path)

        labels = [subdir for _ in range(len(faces))]

        print('Carregadas %d faces da classe: %s' % (len(faces), subdir))

        x.extend(faces)
        y.extend(labels)

    return asarray(x), asarray(y)

trainX, trainy = load_fotos(directory_src="C:\\xampp\\htdocs\\TG_Reconhecimento\\Py\\photo\\faces")

trainX.shape

trainy.shape


# # Certifique-se de que o caminho do modelo seja o correto

model = tf.keras.Model.load_model('C:\\xampp\\htdocs\\TG_Reconhecimento\\Py\\Script\\facenet_keras.h5')

# # Verifique o resumo do modelo
model.summary()
