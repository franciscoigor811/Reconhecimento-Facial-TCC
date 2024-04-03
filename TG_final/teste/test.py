import cv2


camera_url = "http://192.168.0.21:81/stream"


cap = cv2.VideoCapture(camera_url)


if not cap.isOpened():
    print(f"Não foi possível abrir a câmera a partir da URL: {camera_url}")
    exit()

while True:
    # Lê um quadro do vídeo
    ret, frame = cap.read()

    # Exibe o quadro na janela
    cv2.imshow("Câmera IP", frame)

    # Verifica se o usuário pressionou a tecla 'q' para sair
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break


cap.release()
cv2.destroyAllWindows()