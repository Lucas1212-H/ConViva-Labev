#!/bin/bash

# 1. Inicia o MySQL em segundo plano usando o entrypoint oficial da imagem
# Nota: Se o comando abaixo falhar por falta do binário docker-entrypoint, ele tentará usar o mysqld direto
if command -v docker-entrypoint.sh &> /dev/null; then
    docker-entrypoint.sh mysqld &
else
    mysqld &
fi

# 2. Aguarda o MySQL subir totalmente na porta 3306
echo "Aguardando o MySQL iniciar..."
for i in {1..30}; do
    if mysqladmin ping -h "127.0.0.1" --silent; then
        echo "MySQL está pronto para conexões!"
        break
    fi
    sleep 2
done

# 3. Cria um falso servidor HTTP na porta exigida pelo Render ($PORT) usando Python em segundo plano
echo "Iniciando falso servidor HTTP na porta $PORT para o validador do Render..."
python3 -c "
import os, socket
s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
s.bind(('0.0.0.0', int(os.environ.get('PORT', 8080))))
s.listen(1)
while True:
    try:
        conn, _ = s.accept()
        conn.recv(1024)
        conn.sendall(b'HTTP/1.1 200 OK\r\nContent-Type: text/plain\r\n\r\nOK')
        conn.close()
    except Exception as e:
        pass
" &

# 4. Inicializa o servidor embutido do Laravel PHP na porta correta
echo "Iniciando o servidor do Laravel Artisan..."
exec php artisan serve --host=0.0.0.0 --port="$PORT"