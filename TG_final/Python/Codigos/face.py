import numpy as np
import os
import cv2
import tkinter as tk
from tkinter import *
import face_recognition
from os import listdir
from os.path import isfile, join
from datetime import datetime, timedelta 
from PIL import Image, ImageDraw, ImageFont
import threading
import banco

last_presence_insertions = {}

bloco = 6
sala = 7

# Inicializar a janela Tkinter
window = tk.Tk()
window.title("Reconhecimento Facial")
window.geometry("800x600")
window.config(background="#080303")

# Carregar imagens de rostos conhecidos
known_faces_path = "C:\\xampp\\htdocs\\TG_FINAL\\Python\\Rostos"
known_face_encodings = []
known_face_names = []

for filename in listdir(known_faces_path):
    if isfile(join(known_faces_path, filename)):
        image = face_recognition.load_image_file(join(known_faces_path, filename))
        face_encoding = face_recognition.face_encodings(image)[0]
        known_face_encodings.append(face_encoding)
        known_face_names.append(os.path.splitext(filename)[0])

# Inicializar a variável para controlar o último frame
last_frame = None

# Ajustar a taxa de processamento para 10 quadros por segundo
frame_interval = 10 # Milissegundos

# Limiar para detecção de movimento
motion_threshold = 50000

# Variável para controlar a execução do thread
running = True

# Função para reconhecimento facial em um thread separado
def recognize_face_thread():
    global running  # Declarando que estamos usando a variável global 'running'
    while running:
        ret, frame = cap.read()
        small_frame = cv2.resize(frame, (0, 0), fx=0.25, fy=0.25)
        rgb_small_frame = cv2.cvtColor(small_frame, cv2.COLOR_BGR2RGB)

        face_locations = face_recognition.face_locations(rgb_small_frame)
        face_encodings = face_recognition.face_encodings(rgb_small_frame, face_locations)

        names = []  # Lista para armazenar nomes correspondentes

        for (top, right, bottom, left), face_encoding in zip(face_locations, face_encodings):
            matches = face_recognition.compare_faces(known_face_encodings, face_encoding)
            face_distances = face_recognition.face_distance(known_face_encodings, face_encoding)
            best_match_index = np.argmin(face_distances)

            confidence_threshold = 0.9

            if matches[best_match_index] and face_distances[best_match_index] < confidence_threshold:
                name = known_face_names[best_match_index]
                # Chame a função de cadastro aqui, passando o nome como argumento
                presenceHandler(name)

            else:
                name = "Desconhecido"

            names.append(name)

            top *= 4
            right *= 4
            bottom *= 4
            left *= 4

            cv2.rectangle(frame, (left, top), (right, bottom), (0, 0, 255), 2)
            cv2.putText(frame, name, (left + 6, bottom - 6), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (255, 255, 255), 1)

        # Calcula a diferença entre o quadro atual e o último quadro
        global last_frame
        if last_frame is not None:
            frame_diff = cv2.absdiff(last_frame, frame)
            motion = np.sum(frame_diff) > motion_threshold
        else:
            motion = False

        last_frame = frame.copy()

        if motion or names:
            # Adicionar data e hora à imagem
            current_time = datetime.now().strftime("%H:%M %d/%m/%Y")
            img = Image.fromarray(cv2.cvtColor(frame, cv2.COLOR_BGR2RGB))
            draw = ImageDraw.Draw(img)
            font = ImageFont.truetype("arial.ttf", 20)  # Você pode escolher outra fonte que funcione para você
            draw.text((20, 20), current_time, (0, 0, 255), font=font)

            frame = cv2.cvtColor(np.array(img), cv2.COLOR_RGB2BGR)

        # Exibir o quadro com uma taxa de quadros reduzida
        cv2.imshow("Reconhecimento Facial", frame)

        # Verificar se o usuário pressionou a tecla 'q' para sair
        key = cv2.waitKey(frame_interval)
        if key == ord('q'):
            running = False

def presenceHandler(name):
    global last_presence_insertions
    conn = banco.conectar_banco()
    
    if conn:
        current_time = datetime.now()
        
        if name in last_presence_insertions:
            last_insertion_time = last_presence_insertions[name]
            time_elapsed = current_time - last_insertion_time
            
            # Verifique se já se passaram 10 segundos desde a última inserção
            if time_elapsed < timedelta(seconds=10):
                print("Não é possível inserir a presença de", name, "novamente tão cedo.")
                return

        if banco.inserir_presenca(conn, name, sala, bloco) == True:
            last_presence_insertions[name] = current_time
            print("Presença de", name, "inserida com sucesso.")
        else:
            print("Não foi possível inserir a presença de", name)
        

# Inicializar a câmera com uma resolução menor
camera_url = "http://192.168.43.227:81/stream"
cap = cv2.VideoCapture(0)
cap.set(cv2.CAP_PROP_FRAME_WIDTH, 640)
cap.set(cv2.CAP_PROP_FRAME_HEIGHT, 480)

# Criar um thread para executar a função de reconhecimento facial
recognize_thread = threading.Thread(target=recognize_face_thread)

# Botão para iniciar o reconhecimento facial
start_button = Button(window, text="Iniciar Reconhecimento Facial", command=recognize_thread.start)
start_button.pack(pady=20)

# Função para fechar a janela
def close_window():
    global running  # Declarando que estamos usando a variável global 'running'
    running = False
    recognize_thread.join()
    cap.release()
    cv2.destroyAllWindows()
    window.destroy()

# Botão para fechar a janela
exit_button = Button(window, text="Sair", command=close_window)
exit_button.pack()

window.mainloop()
