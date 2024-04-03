import mysql.connector

# Configuração de conexão com o banco de dados
db_config = {
    'host': 'localhost',  # Endereço do servidor de banco de dados
    'user': 'root',  # Nome de usuário do banco de dados
    'password': '',  # Senha do banco de dados
    'database': 'tg_2023'  # Nome do banco de dados
}

# Função para conectar ao banco de dados
def conectar_banco():
    try:
        conn = mysql.connector.connect(**db_config)
        print("Conexão ao banco de dados bem-sucedida")
        return conn
    except Exception as e:
        print(f"Erro ao conectar ao banco de dados: {str(e)}")
        return None

# Função para inserir dados em uma tabela
def inserir_presenca(conn, aluno_ra, sala, bloco):
    try:
        aluno_ra = int(aluno_ra)
        materia_id = selecionar_materia(conn, sala, bloco)
        tipo = int(selecionar_ultima_presenca(conn, aluno_ra, materia_id))
        cadastrado = procurar_alunos(conn, materia_id, aluno_ra)

        if(cadastrado == False):
            return False
        else:
            cursor = conn.cursor()

            # Consulta SQL para inserir dados em uma tabela chamada "pessoas"
            sql = "INSERT INTO chamada (tipo, aluno_ra, materia_id) VALUES (%s, %s, %s)"
            values = (tipo, aluno_ra, materia_id)

            cursor.execute(sql, values)

            conn.commit()  # Efetua o commit para salvar as alterações no banco de dados

            print(f"Dados inseridos com sucesso: tipo = {tipo}, aluno_ra = {aluno_ra}, materia_id = {materia_id}")

            cursor.close()
            return True
    except Exception as e:
        print(f"Erro ao inserir dados: {str(e)}")

# Função para selecionar dados de uma tabela
def selecionar_materia(conn,sala,bloco):
    try:
        cursor = conn.cursor()

        # Consulta SQL para selecionar todos os registros da tabela "pessoas"
        sql = f"SELECT id_materia FROM materia WHERE Numero_sala = {sala} AND Numero_bloco = {bloco} AND Hora_inicial <= CURRENT_TIME AND CURRENT_TIME <= (Hora_inicial + Tempo_aula) AND dia_semana = (DAYOFWEEK(CURRENT_DATE) - 1) AND data_inicio <= CURRENT_DATE AND CURRENT_DATE <= data_fim"
        cursor.execute(sql)

        # Recupera todos os resultados da consulta
        resultados = cursor.fetchall()

        if not resultados:
            print("Nenhum dado encontrado na tabela")
        else:
            print("Dados na tabela:")
            for resultado in resultados:
                return resultado[0]

        cursor.close()
        return -1
    except Exception as e:
        print(f"Erro ao selecionar dados: {str(e)}")

def procurar_alunos(conn, materia, RA):
    try:
        cursor = conn.cursor()
        cursorA = conn.cursor()
        print(materia)
        # Consulta SQL para selecionar todas as turmas cadastradas na matéria atual
        sql = f"SELECT turma_id FROM materias_turmas WHERE materia_id = {materia}"
        
        cursor.execute(sql)

        # Recupera todos os resultados da consulta
        resultados = cursor.fetchall()

        if not resultados:
            print("Nenhuma turma cadastrada na matéria atual")
        else:
            # print("Dados na tabela:")
            for resultado in resultados:
                sqlA = f"SELECT aluno_RA FROM turma_alunos WHERE Turma_id = {resultado[0]}"
                cursorA.execute(sqlA)
                resultadosA = cursor.fetchall()

                if not resultadosA:
                    print("Nenhum aluno cadastrado na turma")
                else:
                    for resultadoA in resultadosA:
                        if(resultadoA[0] == RA):
                            return True
                        
            cursorA.close()
        cursor.close()
        return False
    except Exception as e:
        print(f"Erro ao selecionar dados: {str(e)}")



def selecionar_ultima_presenca(conn, aluno_ra, materia_id):
    try:
        cursor = conn.cursor()

        # Consulta SQL para selecionar o último registro da tabela "chamada" com base na data_hora
        sql = f"SELECT tipo FROM chamada WHERE aluno_ra = {aluno_ra} AND materia_id = {materia_id} ORDER BY data_hora DESC LIMIT 1;"
        cursor.execute(sql)

        # Recupera todos os resultados da consulta
        resultados = cursor.fetchall()

        if not resultados:
            return 1
        else:
            return 1 - int(resultados[0][0])

    except Exception as e:
        print(f"Erro ao selecionar dados: {str(e)}")
    finally:
        cursor.close()


# Função principal
def main():
    conn = conectar_banco()
    if conn:
        # # Insere dados na tabela
        # inserir_dados(conn, "João", 30)
        # inserir_dados(conn, "Maria", 25)

        # # Seleciona dados da tabela
        # selecionar_dados(conn)

        ##inserir_presenca(conn, 1910221, 2)

        #print(inserir_presenca(conn, 1910221, 7, 6))


        conn.close()  # Fecha a conexão com o banco de dados

if __name__ == "__main__":
    main()
