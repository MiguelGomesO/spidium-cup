# Spidium Cup

Sistema web para gestão de campeonatos de futebol amador. Permite criar torneios em diferentes formatos, registrar partidas com súmula em tempo real, acompanhar classificação e artilheiros, e publicar resultados em uma página aberta ao público.

## Funcionalidades

### Campeonatos
- Três formatos de disputa:
  - **Liga** — todos contra todos, com tabela de classificação.
  - **Grupos** — fase de grupos (mínimo 8 times) com classificação por grupo.
  - **Mata-mata** — chave eliminatória com avanço automático do vencedor.
- Inscrição de times, geração automática de grupos, partidas e chaveamento.

### Times e jogadores
- Cadastro de times e elencos.
- Escalação por partida.
- Links sociais nos perfis dos jogadores.
- Histórico de partidas e ranking de artilheiros por time.

### Partidas
- Status: em andamento, ao vivo ou finalizada.
- Súmula com eventos: gols (com assistência), cartão amarelo e cartão vermelho.
- Partidas amistosas (sem campeonato vinculado).
- Controle de participações (jogadores que entraram em campo).

### Área pública
- `/resultados` — listagem de campeonatos e detalhes com classificação, chaves, partidas e artilheiros, sem necessidade de login.

### Painel administrativo
- Dashboard com estatísticas gerais.
- CRUD completo de campeonatos, times e partidas.
- Autenticação com Laravel Breeze (registro, login, recuperação de senha).

## Stack

| Camada        | Tecnologia                          |
|---------------|-------------------------------------|
| Backend       | PHP 8.2+, Laravel 12                |
| Frontend      | Blade, Tailwind CSS, Alpine.js      |
| Build         | Vite 7                              |
| Banco de dados| SQLite (padrão) ou MySQL/MariaDB  |
| Autenticação  | Laravel Breeze                      |

## Requisitos

- PHP >= 8.2 com extensões: `pdo`, `sqlite3` (ou `pdo_mysql`), `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) >= 18 e npm

## Instalação

Clone o repositório e entre na pasta do projeto:

```bash
git clone <url-do-repositorio> spidium-cup
cd spidium-cup
```

Instale dependências, configure o ambiente e rode as migrations com um único comando:

```bash
composer setup
```

Esse script executa: `composer install`, cria o `.env`, gera a `APP_KEY`, roda as migrations, instala pacotes npm e compila os assets.

### Instalação manual

```bash
composer install
cp .env.example .env   # no Windows: copy .env.example .env
php artisan key:generate
touch database/database.sqlite   # no Windows: type nul > database/database.sqlite
php artisan migrate
npm install
npm run build
```

### Banco MySQL (opcional)

No `.env`, ajuste as variáveis de conexão:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=spidium_cup
DB_USERNAME=root
DB_PASSWORD=
```

Crie o banco de dados e execute `php artisan migrate`.

### Usuário padrão (seed)

Para popular um usuário administrador de desenvolvimento:

```bash
php artisan db:seed
```

| E-mail            | Senha   |
|-------------------|---------|
| admin@admin.com   | 123456  |

## Executando o projeto

Ambiente de desenvolvimento (servidor PHP, fila, logs e Vite em paralelo):

```bash
composer dev
```

Ou separadamente:

```bash
php artisan serve
npm run dev
```

Acesse:
- **Resultados públicos:** http://localhost:8000/resultados
- **Painel (login):** http://localhost:8000/login
- **Dashboard:** http://localhost:8000/dashboard

## Testes

```bash
composer test
# ou
php artisan test
```

## Estrutura principal

```
app/
├── Http/Controllers/     # Campeonato, Time, Partida, Resultados, Eventos...
├── Models/               # Campeonato, Time, Jogador, Partida, Grupo...
└── Http/Controllers/Concerns/
    └── CalculaEstatisticasCampeonato.php

resources/views/
├── campeonatos/          # Gestão e layouts por formato (liga, grupos, mata_mata)
├── partidas/             # Súmula e listagem de jogos
├── resultados/           # Páginas públicas
├── times/                # Times e escalação
└── dashboard.blade.php   # Painel inicial

routes/
├── web.php               # Rotas da aplicação
└── auth.php              # Rotas de autenticação (Breeze)
```

## Rotas principais

| Rota | Descrição |
|------|-----------|
| `GET /resultados` | Lista pública de campeonatos |
| `GET /resultados/{campeonato}` | Detalhes e classificação do campeonato |
| `GET /dashboard` | Painel administrativo |
| `resource /campeonatos` | CRUD de campeonatos |
| `resource /times` | CRUD de times |
| `resource /partidas` | CRUD de partidas e súmula |

Rotas administrativas exigem autenticação (`auth` middleware).

## Licença

Este projeto utiliza o framework [Laravel](https://laravel.com), licenciado sob a [MIT License](https://opensource.org/licenses/MIT).
