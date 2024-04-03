import numpy as np
import os
import cv2
import tkinter as tk
from tkinter import *
##from tkinter import messagebox
##from PIL import Image
##from PIL import ImageTk
import face_recognition
from os import listdir
from os.path import isfile, join
from datetime import datetime ##, timedelta

# Inicializar a janela Tkinter
window = tk.Tk()
window.title("Reconhecimento Facial")
window.geometry("800x600")
window.config(background="#080303")

# Carregar imagens de rostos conhecidos
known_faces_path = "C:\\xampp\\htdocs\\TG_Reconhecimento\\Python\\Rostoss"
known_face_encodings = []
known_face_names = []

for filename in listdir(known_faces_path):
    if isfile(join(known_faces_path, filename)):
        image = face_recognition.load_image_file(join(known_faces_path, filename))
        face_encoding = face_recognition.face_encodings(image)[0]
        known_face_encodings.append(face_encoding)
        known_face_names.append(os.path.splitext(filename)[0])

# Inicializar a câmera
#cap = cv2.VideoCapture(0)

camera_url = "http://192.168.0.21:81/stream"
cap = cv2.VideoCapture(0)

# Inicializar a variável para controlar o último frame
last_frame = None

# Função para reconhecimento facial
def recognize_face():
    while True:
        ret, frame = cap.read()

        # Verificar se a captura de vídeo está funcionando corretamente
        if not ret:
            break

        small_frame = cv2.resize(frame, (0, 0), fx=0.25, fy=0.25)
        rgb_small_frame = cv2.cvtColor(small_frame, cv2.COLOR_BGR2RGB)
        face_locations = face_recognition.face_locations(rgb_small_frame)
        face_encodings = face_recognition.face_encodings(rgb_small_frame, face_locations)

        names = []  # Lista para armazenar nomes correspondentes

        for (top, right, bottom, left), face_encoding in zip(face_locations, face_encodings):
            matches = face_recognition.compare_faces(known_face_encodings, face_encoding)
            face_distances = face_recognition.face_distance(known_face_encodings, face_encoding)
            best_match_index = np.argmin(face_distances)

            confidence_threshold = 0.8

            if matches[best_match_index] and face_distances[best_match_index] < confidence_threshold:
                name = known_face_names[best_match_index]
            else:
                name = "Desconhecido"

            names.append(name)

            top *= 4
            right *= 4
            bottom *= 4
            left *= 4

            cv2.rectangle(frame, (left, top), (right, bottom), (0, 0, 255), 2)
            cv2.putText(frame, name, (left + 6, bottom - 6), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (255, 255, 255), 1)

        # Verificar movimento entre frames consecutivos
        global last_frame
        if last_frame is not None:
            frame_diff = cv2.absdiff(last_frame, frame)
            motion = np.sum(frame_diff) > 50000  # Ajuste o valor do limite conforme necessário
        else:
            motion = False

        last_frame = frame.copy()

        if motion and names:
            # Se houver movimento e rostos reconhecidos, exiba o resultado
            # Exibir data e hora em azul
            current_time = datetime.now().strftime("%H:%M %d/%m/%Y")
            cv2.putText(frame, current_time, (20, 20), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 0, 255), 1)  # Alterar a cor para azul
            cv2.imshow("Reconhecimento Facial", frame)
        ##else:
            # Se não houver movimento ou rostos reconhecidos, não exiba nada
            ##cv2.imshow("Reconhecimento Facial", np.zeros((1,1), dtype=np.uint8))  # Exibir uma imagem preta

        if cv2.waitKey(1) & 0xFF == ord('q'):
            break


# Botão para iniciar o reconhecimento facial
start_button = Button(window, text="Iniciar Reconhecimento Facial", command=recognize_face)
start_button.pack(pady=20)

# Função para fechar a janela
def close_window():
    cap.release()
    cv2.destroyAllWindows()
    window.destroy()

# Botão para fechar a janela
exit_button = Button(window, text="Sair", command=close_window)
exit_button.pack()

window.mainloop()