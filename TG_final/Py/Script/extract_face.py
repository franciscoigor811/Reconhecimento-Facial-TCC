from mtcnn import MTCNN
from PIL import Image
from os import listdir, makedirs
from os.path import isdir, join, exists
from numpy import asarray

detector = MTCNN()

def extrair_face(arquivo, size=(160, 160)):
    print("Processando:", arquivo)
    img = Image.open(arquivo)
    img = img.convert('RGB')
    array = asarray(img)
    results = detector.detect_faces(array)

    if results:
        x1, y1, width, height = results[0]['box']

        x2, y2 = x1 + width, y1 + height

        face = array[y1:y2 , x1:x2]

        image = Image.fromarray(face)
        image = image.resize(size)

        return image
    else:
        print("Nenhuma face detectada em:", arquivo)
        return None
    
def flip_image(image):
    if image:
        img = image.transpose(Image.FLIP_LEFT_RIGHT)
        return img
    else:
        return None


def load_fotos(directory_src, directory_target):
    # Certifique-se de que o diretório de destino exista
    if not exists(directory_target):
        makedirs(directory_target)

    for filename in listdir(directory_src):
        path = join(directory_src, filename)
        path_tg = join(directory_target, filename)
        path_tg_flip = join(directory_target, "flip-"+filename)

        try:
            face = extrair_face(path)
            flip = flip_image(face)

            if face:
                face.save(path_tg, "JPEG", quality=100, optimize=True)
                flip.save(path_tg_flip, "JPEG", quality=100, optimize=True)
        except:
            print("erro na imagem{}".format(path))

def load_dir(directory_src, directory_target):
    for subdir in listdir(directory_src):
        path = join(directory_src, subdir)
        path_tg = join(directory_target, subdir)

        if not isdir(path):
            continue

        load_fotos(path, path_tg)

if __name__ == '__main__':
    load_dir("C:\\xampp\htdocs\\TG_Reconhecimento\\Py\\photo\\fotos",
             "C:\\xampp\htdocs\\TG_Reconhecimento\\Py\\photo\\faces")
