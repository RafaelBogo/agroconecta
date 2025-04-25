# Instruções para Rodar o Sistema AgroConecta

## Pré-requisitos

- PHP 8.1 ou superior
- Composer instalado
- MySQL (Workbench ou outro gerenciador de banco de dados)
- Node.js e NPM (opcional, se precisar compilar assets com Laravel Mix)

---

## Passo a Passo

### 1. Clone o repositório (se necessário)

Caso ainda não tenha o projeto na sua máquina:

```bash
git clone [link-do-repositório]
cd AgroConecta
```

### 2. Crie o Banco de Dados

- Acesse o **MySQL Workbench** (ou outra ferramenta).
- Crie um novo banco de dados (schema) com o nome que desejar (por exemplo, `agroconecta`).
  - Apenas crie o banco, não é necessário criar tabelas manualmente.

### 3. Configure o arquivo `.env`

- Copie o arquivo de exemplo:

```bash
cp .env.example .env
```

- Abra o arquivo `.env` e edite as informações do banco de dados:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_banco
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

**Importante**: certifique-se de que o nome do banco de dados no `.env` seja o mesmo que você criou no passo anterior.

### 4. Instale as dependências PHP

Instale as dependências do Laravel usando o Composer:

```bash
composer install
```

### 5. Gere a chave de aplicação

Depois que o Composer instalar tudo, gere a chave de segurança do Laravel:

```bash
php artisan key:generate
```

### 6. Execute as Migrations

Rode as migrations para criar as tabelas no banco de dados:

```bash
php artisan migrate
```

### 7. (Opcional) Compile os Assets (caso tenha frontend com Laravel Mix)

Se o projeto tiver assets (CSS/JS) a serem compilados:

```bash
npm install
npm run dev
```

### 8. Execute o Servidor de Desenvolvimento

Inicie o servidor embutido do Laravel:

```bash
php artisan serve
```

- A aplicação estará disponível normalmente em: [http://localhost:8000](http://localhost:8000)

---

## Observações

- Se você ainda não tem o Laravel Installer globalmente, pode instalar com:

```bash
composer global require laravel/installer
```

- Sempre verifique se o Composer está no PATH para reconhecer o comando `laravel` e `php artisan`.
