# Agenda Fácil

Sistema de agendamento online para prestadores de serviço. Projeto de portfólio desenvolvido com PHP (Laravel), Tailwind CSS, JavaScript e com futuras integrações em Python.

## Visão Geral do Projeto

O "Agenda Fácil" é uma aplicação web multi-tenant que permite a pequenos negócios (como barbearias, salões, clínicas) gerenciar seus serviços, horários de atendimento e agendamentos de clientes de forma simples e eficiente.

## Estrutura do MVP

A primeira versão do projeto focará nas seguintes funcionalidades essenciais:

* **Autenticação de Proprietários:** Donos de negócios podem se registrar e fazer login.
* **Gerenciamento do Negócio:**
    * Cadastro de informações do negócio (nome, ramo).
    * Cadastro de serviços (nome, duração, preço).
    * Definição de horários de trabalho semanais.
* **Página Pública de Agendamento:**
    * Uma página acessível publicamente para cada negócio.
    * Clientes podem visualizar os serviços.
    * Seleção de data e visualização de horários disponíveis.
    * Formulário para realizar o agendamento.
* **Painel Administrativo:**
    * Visualização dos agendamentos marcados.
    * Opção de cancelar um agendamento.

## Tecnologias Utilizadas

* **Backend:** PHP 8+ / Laravel 10+
* **Frontend:** HTML5, Tailwind CSS, JavaScript (Fetch API)
* **Banco de Dados:** MySQL / PostgreSQL
* **Servidor de Desenvolvimento:** Laravel Sail (Docker) ou ambiente local (XAMPP/WAMP)

---

## Dicionário de Dados (Versão Inicial)

A seguir, a estrutura inicial das tabelas do banco de dados.

### Tabela: `users`
Armazena os dados dos proprietários dos negócios que usarão o sistema.

| Coluna | Tipo | Descrição | Chave |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT (PK) | Identificador único do usuário. | Primária |
| `name` | VARCHAR(255) | Nome do proprietário. | |
| `email` | VARCHAR(255) | E-mail para login. | Única |
| `password` | VARCHAR(255) | Senha criptografada. | |
| `created_at`| TIMESTAMP | Data de criação do registro. | |
| `updated_at`| TIMESTAMP | Data da última atualização. | |

### Tabela: `businesses`
Armazena as informações de cada negócio cadastrado.

| Coluna | Tipo | Descrição | Chave |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT (PK) | Identificador único do negócio. | Primária |
| `user_id` | BIGINT (FK) | ID do usuário proprietário. | Estrangeira (`users.id`) |
| `name` | VARCHAR(255) | Nome do negócio. | |
| `slug` | VARCHAR(255) | URL amigável (ex: "barbearia-do-ze"). | Única |
| `branch` | VARCHAR(100) | Ramo de atuação (ex: "Barbearia"). | |
| `created_at`| TIMESTAMP | Data de criação do registro. | |
| `updated_at`| TIMESTAMP | Data da última atualização. | |

### Tabela: `services`
Serviços oferecidos por cada negócio.

| Coluna | Tipo | Descrição | Chave |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT (PK) | Identificador único do serviço. | Primária |
| `business_id`| BIGINT (FK) | ID do negócio que oferece o serviço. | Estrangeira (`businesses.id`) |
| `name` | VARCHAR(255) | Nome do serviço (ex: "Corte Masculino"). | |
| `duration_minutes` | INT | Duração do serviço em minutos. | |
| `price` | DECIMAL(10,2) | Preço do serviço. | |
| `created_at`| TIMESTAMP | Data de criação do registro. | |
| `updated_at`| TIMESTAMP | Data da última atualização. | |

### Tabela: `schedules`
Horários de funcionamento do negócio.

| Coluna | Tipo | Descrição | Chave |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT (PK) | Identificador único do horário. | Primária |
| `business_id`| BIGINT (FK) | ID do negócio. | Estrangeira (`businesses.id`) |
| `day_of_week`| INT | Dia da semana (0=Domingo, 1=Segunda...). | |
| `start_time` | TIME | Hora de início do expediente. | |
| `end_time` | TIME | Hora de fim do expediente. | |

### Tabela: `appointments`
Agendamentos realizados pelos clientes.

| Coluna | Tipo | Descrição | Chave |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT (PK) | Identificador único do agendamento. | Primária |
| `service_id` | BIGINT (FK) | ID do serviço agendado. | Estrangeira (`services.id`) |
| `customer_name`| VARCHAR(255) | Nome do cliente. | |
| `customer_email`| VARCHAR(255) | E-mail do cliente. | |
| `start_at` | DATETIME | Data e hora de início do agendamento. | |
| `end_at` | DATETIME | Data e hora de término do agendamento. | |
| `status` | VARCHAR(20) | Status (ex: "Confirmado", "Cancelado"). | |
| `created_at`| TIMESTAMP | Data de criação do registro. | |
| `updated_at`| TIMESTAMP | Data da última atualização. | |
#