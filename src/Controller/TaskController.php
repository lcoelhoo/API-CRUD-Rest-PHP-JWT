<?php

namespace Controller;

    use Util\Database;
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;
    use PDO; 

    class TaskController {
        private $jwt_secret = "secreto_super_secreto";   
        

        public function __construct() {
            // Verificar a autenticação JWT em todos os métodos, exceto para as URLs de login e signup
            $current_url = $_SERVER['REQUEST_URI'];
            if (strpos($current_url, '/login') === false && strpos($current_url, '/signup') === false) {
                $this->verifyJWT();
            }
        }   

        public function create() {
            // Obter os dados JSON enviados na solicitação e decodificá-los
            $json_data = file_get_contents("php://input");
        
            // Verificar se os dados JSON foram recebidos e decodificados corretamente
            if ($json_data === false || empty($json_data)) {
                http_response_code(400); // Bad Request
                echo json_encode(array("message" => "Nenhum dado JSON recebido."));
                exit();
            }
        
            // Decodificar os dados JSON em um array associativo
            $data = json_decode($json_data, true);
        
            // Verificar se os dados da tarefa foram fornecidos
            if (!isset($data['title']) || !isset($data['description'])) {
                http_response_code(400); // Bad Request
                echo json_encode(array("message" => "O título e a descrição da tarefa são obrigatórios."));
                exit();
            }
        
            // Sanitize os dados da tarefa para evitar injeção de SQL
            $title = filter_var($data['title'], FILTER_SANITIZE_STRING);
            $description = filter_var($data['description'], FILTER_SANITIZE_STRING);
        
            // Conectar-se ao banco de dados e inserir a nova tarefa
            try {
                $db = Database::getConnection();
                $query = "INSERT INTO tasks (title, description) VALUES (:title, :description)";
                $stmt = $db->prepare($query);
                $stmt->bindParam(":title", $title, PDO::PARAM_STR);
                $stmt->bindParam(":description", $description, PDO::PARAM_STR);
                if ($stmt->execute()) {
                    http_response_code(201); // Created
                    echo json_encode(array("message" => "Tarefa criada com sucesso."));
                } else {
                    http_response_code(500); // Internal Server Error
                    echo json_encode(array("message" => "Não foi possível criar a tarefa."));
                }
            } catch (PDOException $e) {
                http_response_code(500); // Internal Server Error
                echo json_encode(array("message" => "Erro ao criar a tarefa: " . $e->getMessage()));
            }
        }
        


        public function read() {
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                http_response_code(405); 
                echo json_encode(array("message" => "Método não permitido."));
                exit();
            }
        
            // Conectar-se ao banco de dados e executar a consulta para recuperar todas as tarefas
            $db = Database::getConnection();
            $query = "SELECT * FROM tasks";
            $stmt = $db->prepare($query);
            $stmt->execute();
            
            // Verificar se há tarefas encontradas
            if ($stmt->rowCount() > 0) {
                $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
                // Retornar os dados das tarefas em formato JSON
                http_response_code(200); // OK
                echo json_encode($tasks);
            } else {
                // Se não houver tarefas encontradas, retornar uma mensagem
                http_response_code(404); // Not Found
                echo json_encode(array("message" => "Nenhuma tarefa encontrada."));
            }
        }


        public function readid() {
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                http_response_code(405); 
                echo json_encode(array("message" => "Método não permitido."));
                exit();
            }
        
            // Obter os dados JSON enviados na solicitação e decodificá-los
            $json_data = file_get_contents("php://input");
        
            // Verificar se os dados JSON foram recebidos e decodificados corretamente
            if ($json_data === false || empty($json_data)) {
                http_response_code(400); // Bad Request
                echo json_encode(array("message" => "Nenhum dado JSON recebido."));
                exit();
            }
        
            // Decodificar os dados JSON em um array associativo
            $task_data = json_decode($json_data, true);
        
            // Verificar se o ID da tarefa foi fornecido
            if (!isset($task_data['id']) || !is_numeric($task_data['id'])) {
                http_response_code(400); // Bad Request
                echo json_encode(array("message" => "É necessário fornecer um ID de tarefa válido."));
                exit();
            }
        
            // Sanitize o ID da tarefa para evitar injeção de SQL
            $task_id = intval($task_data['id']);
        
            // Conectar-se ao banco de dados e executar a consulta para recuperar a tarefa com o ID especificado
            $db = Database::getConnection();
            $query = "SELECT * FROM tasks WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->bindParam(1, $task_id, PDO::PARAM_INT);
            $stmt->execute();
        
            // Verificar se a tarefa foi encontrada
            if ($stmt->rowCount() > 0) {
                $task = $stmt->fetch(PDO::FETCH_ASSOC);
                // Retornar os dados da tarefa em formato JSON
                http_response_code(200); // OK
                echo json_encode($task);
            } else {
                // Se a tarefa não for encontrada, retornar uma mensagem
                http_response_code(404); // Not Found
                echo json_encode(array("message" => "Tarefa não encontrada com o ID fornecido."));
            }
        }
        
        
        


        public function update() {
            if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
                http_response_code(405);
                echo json_encode(array("message" => "Método não permitido."));
                exit();
            }
        
            // Obter os dados JSON enviados na solicitação e decodificá-los
            $json_data = file_get_contents("php://input");
        
            // Verificar se os dados JSON foram recebidos e decodificados corretamente
            if ($json_data === false || empty($json_data)) {
                http_response_code(400); // Bad Request
                echo json_encode(array("message" => "Nenhum dado JSON recebido."));
                exit();
            }
        
            // Decodificar os dados JSON em um array associativo
            $data = json_decode($json_data, true);
        
            // Verificar se os dados necessários estão presentes
            if (!isset($data['id']) || !isset($data['title']) || !isset($data['description'])) {
                http_response_code(400); // Bad Request
                echo json_encode(array("message" => "ID, título e descrição da tarefa são obrigatórios."));
                exit();
            }
        
            // Sanitize os dados da tarefa para evitar injeção de SQL
            $id = filter_var($data['id'], FILTER_VALIDATE_INT);
            $title = filter_var($data['title'], FILTER_SANITIZE_STRING);
            $description = filter_var($data['description'], FILTER_SANITIZE_STRING);
        
            // Conectar-se ao banco de dados e atualizar a tarefa
            try {
                $db = Database::getConnection();
        
                // Verificar se a tarefa com a ID fornecida existe
                $query_check = "SELECT id FROM tasks WHERE id = :id";
                $stmt_check = $db->prepare($query_check);
                $stmt_check->bindParam(":id", $id, PDO::PARAM_INT);
                $stmt_check->execute();
        
                if ($stmt_check->rowCount() === 0) {
                    http_response_code(404); // Not Found
                    echo json_encode(array("message" => "Tarefa não encontrada."));
                    exit();
                }
        
                // Preparar a consulta para atualizar a tarefa
                $query_update = "UPDATE tasks SET title = :title, description = :description WHERE id = :id";
                $stmt_update = $db->prepare($query_update);
                $stmt_update->bindParam(":title", $title, PDO::PARAM_STR);
                $stmt_update->bindParam(":description", $description, PDO::PARAM_STR);
                $stmt_update->bindParam(":id", $id, PDO::PARAM_INT);
        
                // Executar a consulta de atualização
                if ($stmt_update->execute()) {
                    http_response_code(200); // OK
                    echo json_encode(array("message" => "Tarefa atualizada com sucesso."));
                } else {
                    http_response_code(500); // Internal Server Error
                    echo json_encode(array("message" => "Não foi possível atualizar a tarefa."));
                }
            } catch (PDOException $e) {
                http_response_code(500); // Internal Server Error
                echo json_encode(array("message" => "Erro ao atualizar a tarefa: " . $e->getMessage()));
            }
        }      
            
        
        

        public function delete() {
            if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
                http_response_code(405); // Method Not Allowed
                echo json_encode(array("message" => "Método não permitido."));
                exit();
            }
        
            // Obtenha os dados JSON enviados na solicitação e decodifique-os
            $json_data = file_get_contents("php://input");
            $data = json_decode($json_data, true);
        
            // Verificar se os dados JSON foram decodificados corretamente e se o ID da tarefa foi fornecido
            if ($json_data === false || $data === null || !isset($data['id'])) {
                http_response_code(400); // Bad Request
                echo json_encode(array("message" => "ID da tarefa é obrigatório."));
                exit();
            }
        
            // Sanitize o ID da tarefa, evita injeção de SQL
            $task_id = filter_var($data['id'], FILTER_VALIDATE_INT);
            if ($task_id === false) {
                http_response_code(400); // Bad Request
                echo json_encode(array("message" => "ID da tarefa inválido."));
                exit();
            }
        
            try {
                // Conectar-se ao banco de dados
                $db = Database::getConnection();    
                
                // Verificar se a tarefa com a ID fornecida existe
                $query_check = "SELECT id FROM tasks WHERE id = :id";
                $stmt_check = $db->prepare($query_check);
                $stmt_check->bindParam(":id", $task_id, PDO::PARAM_INT);
                $stmt_check->execute();
        
                if ($stmt_check->rowCount() === 0) {
                    http_response_code(404); 
                    echo json_encode(array("message" => "Tarefa não encontrada."));
                    exit();
                }
        
                // Preparar a consulta para excluir a tarefa
                $query_delete = "DELETE FROM tasks WHERE id = :id";
                $stmt_delete = $db->prepare($query_delete);
                $stmt_delete->bindParam(":id", $task_id, PDO::PARAM_INT);
        
                if ($stmt_delete->execute()) {
                    http_response_code(200); 
                    echo json_encode(array("message" => "Tarefa excluída com sucesso."));
                } else {
                    http_response_code(500);
                    echo json_encode(array("message" => "Não foi possível excluir a tarefa."));
                }
            } catch (PDOException $e) {
                http_response_code(500); 
                echo json_encode(array("message" => "Erro ao excluir a tarefa: " . $e->getMessage()));
            }
        }
        

        private function verifyJWT() {
            $headers = getallheaders();
            $token = isset($headers['Authorization']) ? $headers['Authorization'] : '';
        
            if (!$token) {
                http_response_code(401); 
                echo json_encode(array("message" => "Token JWT ausente."));
                exit();
            }
        
            if (strpos($token, 'Bearer ') === 0) {
                $token = substr($token, 7);
        
                try {
                    $decoded = JWT::decode($token, new Key($this->jwt_secret, 'HS256'));
                    // echo json_encode(array("token_decodificado" => $decoded)) . "\n";
                } catch (\Exception $e) {
                    echo json_encode(array("message" => "Erro ao decodificar o token JWT: " . $e->getMessage())) . "\n";
                    http_response_code(401); 
                    echo json_encode(array("message" => "Falha ao decodificar o token JWT."));
                    exit();
                }
            } else {
                http_response_code(401);
                echo json_encode(array("message" => "Formato de token JWT inválido."));
                exit();
            }
        }       
            
        

    }
