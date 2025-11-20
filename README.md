# Sistema de Marketplace – Ambiente de Testes (Mercado Pago)

Este repositório contém o código-fonte do sistema de marketplace desenvolvido para o TCC.
**Todo o fluxo de pagamento está configurado para funcionar apenas em AMBIENTE DE TESTES.**

> **Avisos importantes**
>
> * Este projeto não é um ambiente de produção.
> * Todas as contas, CPFs e cartões descritos aqui são dados de teste fornecidos pelo Mercado Pago.
> * Nenhuma transação realizada neste sistema gera cobrança real.
> * O sistema foi desenvolvido para rodar em hospedagem (servidor com URL pública).
>   Em execução local (`localhost`) a API de pagamentos não funciona corretamente.

---

## 1. Visão geral do ambiente de testes

O sistema implementa um fluxo de compra e venda integrado à API de pagamentos do Mercado Pago.
Todo o uso descrito neste README destina-se exclusivamente a testes com contas e cartões de sandbox.
O arquivo `.env` utilizado em produção de testes não é disponibilizado neste repositório.

---

## 2. Contas de teste do Mercado Pago

Para simular compras e recebimentos são utilizadas duas contas de teste do Mercado Pago.

### 2.1. Conta de comprador – Buyer Test

Utilizada para simular o cliente que realiza o pagamento.

* **User ID:** `2928363894`
* **Usuário para login:** `TESTUSER3994048825872853384`
* **Senha:** `Ql8mRUfAyd`

Durante o fluxo de checkout, ao ser redirecionado para o Mercado Pago, o login deve ser feito com esses dados.

### 2.2. Conta de vendedor – Seller Test

Utilizada para simular o recebedor do pagamento.

* **User ID:** `2928363900`
* **Usuário para login:** `TESTUSER2474618358938528258`
* **Senha:** `nnzhp9tYXH`

As credenciais de produção (Access Token, Public Key e demais chaves) desta conta devem estar configuradas no arquivo `.env` do projeto, no servidor de hospedagem.

O `.env` não é versionado neste repositório e deve ser criado diretamente no ambiente de execução.

---

## 3. Configuração da API do Mercado Pago no `.env`

No servidor de hospedagem:

1. Copiar o arquivo de exemplo, se existir:
   `cp .env.example .env`
2. Configurar banco de dados e demais parâmetros da aplicação.
3. Informar as credenciais de produção da conta Seller Test nas variáveis de ambiente utilizadas pelo projeto.

Exemplo genérico (ajustar para os nomes efetivamente usados):

```env
MERCADOPAGO_ACCESS_TOKEN=seu_access_token_de_producao_da_conta_seller_test
MERCADOPAGO_PUBLIC_KEY=sua_public_key_de_producao_da_conta_seller_test
```

Sem essas credenciais a aplicação não consegue criar ou consultar pagamentos.

---

## 4. Cartões de teste

Os pagamentos devem ser realizados somente com cartões de teste.
Exemplos de cartões de sandbox:

| Bandeira         | Número do cartão      | Código de segurança | Validade |
| ---------------- | --------------------- | ------------------- | -------- |
| Mastercard       | `5031 4332 1540 6351` | `123`               | `11/30`  |
| Visa             | `4235 6477 2802 5682` | `123`               | `11/30`  |
| American Express | `3753 651535 56885`   | `1234`              | `11/30`  |
| Elo Débito       | `5067 7667 8388 8311` | `123`               | `11/30`  |

Esses cartões são fictícios e não estão vinculados a instituições financeiras reais.

---

## 5. Usuários de teste no sistema

O sistema pode conter usuários de teste cadastrados para facilitar o fluxo de validação.
Esses usuários têm finalidade exclusiva de demonstração e não devem ser usados em ambiente real.

As credenciais de login da aplicação (usuários internos do sistema) não são fornecidas neste repositório e devem ser criadas ou configuradas conforme a necessidade do ambiente de testes.

---

## 6. Status de pagamento retornados pela API

Durante os testes, a API do Mercado Pago pode retornar diferentes detalhes de status de pagamento.
A tabela a seguir apresenta exemplos de códigos de status, com o status de pagamento pendente propositalmente omitido:

| Status | Descrição                                      | Documento de identidade de teste |
| ------ | ---------------------------------------------- | -------------------------------- |
| `APRO` | Pagamento aprovado                             | (CPF) `12345678909`              |
| `OTHE` | Recusado por erro geral                        | (CPF) `12345678909`              |
| `CALL` | Recusado com validação para autorizar          | `-`                              |
| `FUND` | Recusado por quantia insuficiente              | `-`                              |
| `SECU` | Recusado por código de segurança inválido      | `-`                              |
| `EXPI` | Recusado por problema com a data de vencimento | `-`                              |
| `FORM` | Recusado por erro no formulário                | `-`                              |

Esses códigos permitem validar o comportamento do sistema frente a cenários de sucesso e recusa.

---

## 7. Passo a passo para testar um pagamento

### 7.1. Subir o projeto em hospedagem

1. Fazer upload dos arquivos do projeto para um servidor com suporte a PHP e Laravel.
2. Configurar o domínio ou subdomínio apontando o diretório público para a pasta `public/` do projeto.
3. Garantir que a aplicação esteja acessível por uma URL pública.

Execução em `localhost` não é suportada para o fluxo de pagamentos, pois a API do Mercado Pago depende de uma URL acessível externamente.

### 7.2. Configurar o `.env` no servidor

1. Criar o arquivo `.env` com base no `.env.example`.
2. Configurar parâmetros de banco de dados e demais chaves da aplicação.
3. Inserir as credenciais de produção da conta Seller Test conforme seção 3.
4. Executar migrações e seeders, conforme necessário:

```bash
php artisan migrate
php artisan db:seed
```

### 7.3. Iniciar o fluxo de compra no sistema

1. Acessar a URL pública do sistema.
2. Autenticar-se como usuário da aplicação (comprador) ou criar um novo usuário de teste.
3. Adicionar produtos ao carrinho e prosseguir até a etapa de pagamento.

### 7.4. Login no Mercado Pago como Buyer Test

Quando o sistema redirecionar para o Mercado Pago:

1. Informar as credenciais da conta Buyer Test:
   * User ID: `2928363894`
   * Usuário: `TESTUSER3994048825872853384`
   * Senha: `Ql8mRUfAyd`

    Caso peça uma verificação de 6 digitos, são os últimos 6 digitos do UserId.

2. Prosseguir para pagamento com cartão.

### 7.5. Utilizar um cartão de teste

1. Preencher os dados de cartão utilizando uma das combinações da seção 4.
2. Confirmar o pagamento na tela do Mercado Pago.

### 7.6. Verificar o resultado no sistema

1. Após a confirmação ou recusa, o Mercado Pago redireciona de volta ao sistema.
2. O backend consulta a API usando as credenciais de produção da conta Seller Test configuradas no `.env`.
3. O status do pedido é atualizado conforme a resposta (`APRO`, `OTHE`, `FUND`, `SECU`, `EXPI`, `FORM` ou outro status de teste aplicável).

---

## 8. Limitações e escopo

* Ambiente exclusivamente de testes.
* Contas, CPFs e cartões são fictícios e fornecidos pelo sandbox do Mercado Pago.
* O arquivo `.env` não é distribuído neste repositório e deve ser configurado manualmente em cada servidor.
* O uso em produção real exige novas credenciais de Mercado Pago, ambiente separado e revisão de segurança completa.

---

## 9. Execução local (opcional)

O sistema foi desenvolvido para uso em hospedagem com URL pública.
A execução local não é recomendada para o fluxo de pagamentos, pois a API do Mercado Pago depende de notificações e chamadas externas que exigem um endereço acessível pela internet.

Se ainda assim for necessário executar o sistema localmente para fins de desenvolvimento da aplicação em si (sem garantia de funcionamento completo da API de pagamentos), são necessários os pontos a seguir.

### 9.1. Dependências locais

* PHP compatível com a versão requerida pelo Laravel utilizado no projeto.
* Composer instalado.
* XAMPP instalado e configurado (Apache e MySQL ativos) ou ambiente equivalente.
* Servidor de banco de dados MySQL ou compatível.
* Pasta `vendor` presente no projeto. Se não existir, executar:

```bash
composer install
```

### 9.2. Exemplo de configuração de `.env` para ambiente local

O arquivo `.env` deve ser ajustado conforme as necessidades do projeto e do ambiente local.
Abaixo segue um exemplo de configuração básica:

```env
APP_NAME=Agroconecta
APP_ENV=local
APP_KEY=base64:sua_chave_gerada_pelo_artisan
APP_DEBUG=true
APP_TIMEZONE=America/Sao_Paulo
APP_URL=http://localhost

APP_LOCALE=pt_BR
APP_FALLBACK_LOCALE=pt_BR
APP_FAKER_LOCALE=pt_BR

APP_MAINTENANCE_DRIVER=file
PHP_CLI_SERVER_WORKERS=4
BCRYPT_ROUNDS=12

LOG_CHANNEL=errorlog
LOG_LEVEL=debug
LOG_DEPRECATIONS_CHANNEL=null

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=agroconecta_local
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

CACHE_DRIVER=file
CACHE_STORE=file
CACHE_PREFIX=

FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
MEMCACHED_HOST=127.0.0.1

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu_email@gmail.com
MAIL_PASSWORD="sua_senha_ou_app_password"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply.agroconecta@gmail.com
MAIL_FROM_NAME="AgroConecta"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"

MP_PUBLIC_KEY=SEU_MP_PUBLIC_KEY_DE_TESTE
MP_ACCESS_TOKEN=SEU_MP_ACCESS_TOKEN_DE_TESTE

# Exemplo alternativo comentado de chaves do Mercado Pago:
# MERCADOPAGO_PUBLIC_KEY=SEU_OUTRO_PUBLIC_KEY
# MERCADOPAGO_ACCESS_TOKEN=SEU_OUTRO_ACCESS_TOKEN
```

Mesmo com a configuração local do `.env` e o XAMPP corretamente configurado, a API de pagamentos pode não funcionar corretamente em `localhost` devido à necessidade de URLs públicas para callbacks e webhooks.
Para testar o fluxo de pagamento completo, manter a recomendação de uso em ambiente de hospedagem com domínio público.

A estrutura do banco de dados utilizada pelo sistema estará disponível no arquivo `database.sql` neste repositório, para referência e implantação em diferentes ambientes.
