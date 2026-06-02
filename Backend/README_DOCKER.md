# Docker / Render instructions for Backend

Este arquivo descreve como construir e executar a API Laravel localmente com Docker e como proceder para deploy em Render usando o `Dockerfile` incluído.

Local (docker-compose)

1. Copie o `.env.example` para `.env` e ajuste variáveis (DB_HOST=db, DB_USERNAME, etc.).
2. Build e subir containers:

```bash
cd Backend
docker-compose build
docker-compose up -d

# (apenas uma vez) gerar key e rodar migrations
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
```

3. A API estará disponível em `http://localhost:8000`.

Notas sobre Render

- No painel do Render crie um novo Web Service do tipo Docker (Dockerfile).
- Configure as environment variables no dashboard do Render (APP_KEY, APP_ENV, APP_DEBUG=false, DB_* se usar um serviço de banco de dados externo, etc.).
- Render define a porta via a variável de ambiente `PORT`; o `Dockerfile` usa a variável `PORT` e roda `php artisan serve --port=${PORT}`.

Conexão com seu banco externo (Render)

Se você já subiu um banco no Render e quer que a API aponte para ele, configure as variáveis de ambiente no painel do seu serviço Web no Render exatamente como abaixo:

- `DB_CONNECTION`: mysql
- `DB_PORT`: 3306
- `DB_DATABASE`: mysql-conviva
- `DB_USERNAME`: laravel_user
- `DB_PASSWORD`: 123456
- `DB_HOST`: meu-projeto-mysql.onrender.com

Observação importante: no `DB_HOST` remova o prefixo `https://` — use apenas o hostname (por exemplo `meu-projeto-mysql.onrender.com`).

Exemplo: no painel do Render, em Environment → Environment Variables adicione as entradas acima. Depois faça o deploy/reenable do serviço para que as variáveis entrem em vigor.

Recomendações (produção):
- O `php artisan serve` não é uma solução de alto desempenho para produção. Para produção, prefira uma imagem com Nginx+PHP-FPM ou um setup com containers separados (Nginx + php-fpm) e um processo supervisor.
- Proteja o `.env` e configure secrets no painel do Render.

Exemplo rápido de como testar local apontando para esse banco externo (se o IP/porta for acessível):

```bash
cd Backend
# cria .env local a partir do exemplo (ou edite manualmente)
cp .env.example .env
# edite .env e ajuste as variáveis DB_* para apontar para o banco externo
docker-compose build
docker-compose up -d
```

