<?php

namespace Controller;

    use Util\Database;
    use Firebase\JWT\JWT;

    class AuthController {
        private $jwt_secret = "secreto_super_secreto";


        public function signup() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents("php://input"), true);
        
                // Sanitize os dados de entrada para evitar injeção de SQL
                $username = filter_var($data['username'], FILTER_SANITIZE_STRING);
                $password = filter_var($data['password'], FILTER_SANITIZE_STRING);
        
                // Verificar se o nome de usuário e a senha foram fornecidos
                if (empty($username) || empty($password)) {
                    http_response_code(400); // Bad Request
                    echo json_encode(array("message" => "O nome de usuário e a senha são obrigatórios."));
                    return;
                }
        
                // Conectar ao banco de dados
                $db = Database::getConnection();
                // Verificar se o usuário já existe
                $query = "SELECT id FROM users WHERE username = :username";
                $stmt = $db->prepare($query);
                $stmt->bindParam(":username", $username);
                $stmt->execute();
        
                if ($stmt->rowCount() > 0) {
                    http_response_code(400);
                    echo json_encode(array("message" => "Este nome de usuário já está em uso."));
                    return;
                }
        
                // Criptografar a senha
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $query = "INSERT INTO users (username, password) VALUES (:username, :password)";
                $stmt = $db->prepare($query);
                $stmt->bindParam(":username", $username);
                $stmt->bindParam(":password", $hashed_password);
                $stmt->execute();
        
                http_response_code(201); 
                echo json_encode(array("message" => "Usuário cadastrado com sucesso."));
            } else {
                http_response_code(405);
                echo json_encode(array("message" => "Método não permitido. Use POST."));
            }
        }
        


        public function login() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents("php://input"), true);
        
                // Sanitize os dados de entrada para evitar injeção de SQL
                $username = filter_var($data['username'], FILTER_SANITIZE_STRING);
                $password = filter_var($data['password'], FILTER_SANITIZE_STRING);
        
                // Verificar se o nome de usuário e a senha foram fornecidos
                if (empty($username) || empty($password)) {
                    http_response_code(400); // Bad Request
                    echo json_encode(array("message" => "O nome de usuário e a senha são obrigatórios."));
                    return;
                }
        
                // Conectar ao banco de dados
                $db = Database::getConnection();
        
                // Verificar se as credenciais estão corretas
                $query = "SELECT id, username, password FROM users WHERE username = :username";
                $stmt = $db->prepare($query);
                $stmt->bindParam(":username", $username);
                $stmt->execute();
        
                if ($stmt->rowCount() === 0) {
                    http_response_code(401); 
                    echo json_encode(array("message" => "Credenciais inválidas."));
                    return;
                }
        
                $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        
                if (!password_verify($password, $user['password'])) {
                    http_response_code(401); 
                    echo json_encode(array("message" => "Credenciais inválidas."));
                    return;
                }
        
                // Gerar token JWT
                $payload = [
                    "user_id" => $user['id'],
                    "username" => $user['username'],
                    "exp" => time() + 3600 // Token expira em 1 hora(3600)
                ];
        
                $token = JWT::encode($payload, $this->jwt_secret, 'HS256');
        
                http_response_code(200);
                echo json_encode(array("token" => $token));
            } else {
                http_response_code(405);
                echo json_encode(array("message" => "Método não permitido. Use POST."));
            }
        }
        
    }
