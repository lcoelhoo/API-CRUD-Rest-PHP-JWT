# Documentação da API de Gerenciamento de Lista de Tarefas
A API permite a criação, leitura, atualização e exclusão de tarefas, além de autenticação de usuário através de tokens JWT.

-------------------------------------------------------------------------------------------
# Modelagem do banco de dados(MySQL)
## Estrutura para tabela `users`
`CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;`

## Estrutura para tabela `tasks`
`CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('todo','in_progress','done') DEFAULT 'todo',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;`

-------------------------------------------------------------------------------------------
# Operações para usuários
## Registro de Usuário
**Endpoint:** http://localhost/apirestphp/signup <br/>
**Método HTTP:** POST <br/>
**Descrição:** Cria um usuário. <br/>
**Parâmetros de entrada:** <br/>
email (string): O e-mail de usuário. <br/>
password (string): A senha de usuário. <br/>
**Exemplo de parâmetros de entrada(Body no formato JSON):** <br/><br/>
`{
    "username": "teste1",
    "password": "teste123"
}`<br/><br/>
**Formato de dados de resposta:** Usuário cadastrado com sucesso. <br/>

## Autenticação de Usuário
**Endpoint:** http://localhost/apirestphp/login <br/>
**Método HTTP:** POST <br/>
**Descrição:** Autentica um usuário e gera um token JWT para acesso aos endpoints protegidos. <br/>
**Parâmetros de entrada:** <br/>
email (string): O e-mail do usuário. <br/>
password (string): A senha do usuário. <br/>
**Exemplo de parâmetros de entrada(Body no formato JSON):** <br/><br/>
`{
    "username": "teste1",
    "password": "teste123"
}` <br/><br/>
**Formato de dados de resposta: Token (string):** O token JWT gerado para o usuário autenticado.<br/>

-------------------------------------------------------------------------------------------

# Operações CRUD para Tarefas
Em todas operações deve ser feito a autenticação de usuário através do token JWT.

## Criar Tarefa
**Endpoint:** http://localhost/apirestphp/tasks-create <br/>
**Método HTTP:** POST <br/>
**Descrição:** Adiciona uma nova tarefa. <br/>
**Parâmetros de entrada:** <br/>
title (string): O título da tarefa (obrigatório). <br/>
description (string): A descrição da tarefa (obrigatório). <br/>
**Exemplo de parâmetros de entrada(Body no formato JSON):** <br/><br/>
`{
    "title": "Minha nova tarefa",
    "description": "Esta é a descrição da minha nova tarefa."
}`<br/><br/>
**Formato de dados de resposta:** Tarefa criada com sucesso. <br/>

## Listar Todas as Tarefas
**Endpoint:** http://localhost/apirestphp/tasks-read <br/>
**Método HTTP:** GET <br/>
**Descrição:** Lista todas as tarefas do usuário autenticado. <br/>
**Formato de dados de resposta:** Uma lista de todas as tarefas do usuário. <br/>

## Listar a Tarefa por ID
**Endpoint:** http://localhost/apirestphp/tasks-readid <br/>
**Método HTTP:** GET <br/>
**Descrição:** Lista a tarefa com o usuário autenticado. <br/>
**Parâmetros de entrada:** <br/>
id (integer): O identificador único da tarefa a ser buscada. <br/>
**Exemplo de parâmetros de entrada(Body no formato JSON): ** <br/><br/>
`{
  "id": 1
}`<br/><br/>
**Formato de dados de resposta:** Apenas a tarefa buscada por ID. <br/>

## Atualizar Tarefa
**Endpoint:** http://localhost/apirestphp/tasks-update <br/>
**Método HTTP:** PUT ou PATCH <br/>
**Descrição:** Atualiza o título e/ou a descrição de uma tarefa existente. <br/>
**Parâmetros de entrada:** <br/>
id (integer): O identificador único da tarefa. <br/>
title (string): O novo título da tarefa (opcional). <br/>
description (string): A nova descrição da tarefa (opcional). <br/>
**Exemplo de parâmetros de entrada(Body no formato JSON):** <br/><br/>
`{
    "id": 1,
    "title": "Novo nome da tarefa",
    "description": "Nova descrição da tarefa"
}`<br/><br/>
**Formato de dados de resposta:** Tarefa atualizada com sucesso. <br/>

## Deletar Tarefa
**Endpoint:** http://localhost/apirestphp/tasks-delete <br/>
**Método HTTP:** DELETE <br/>
**Descrição:** Remove uma tarefa existente. <br/>
**Parâmetros de entrada:** <br/>
id (integer): O identificador único da tarefa a ser excluída. <br/>
**Exemplo de parâmetros de entrada(Body no formato JSON): ** <br/><br/>
`{
  "id": 1
}`<br/><br/>
**Formato de dados de resposta:** Tarefa excluída com sucesso. <br/>
