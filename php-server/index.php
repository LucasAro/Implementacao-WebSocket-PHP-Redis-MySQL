<?php
require_once 'db.php'; // Inclui o arquivo de conexão com o banco de dados

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['message']) && isset($data['targetId'])) {
    $message = $data['message'];
    $targetId = $data['targetId'];

    try {
        // Salvar no banco de dados (opcional)
        $stmt = $pdo->prepare("INSERT INTO messages (message) VALUES (:message)");
        $stmt->bindParam(':message', $message, PDO::PARAM_STR);
        $stmt->execute();

        // Publicar no Redis
        $redis = new Redis();
        $redis->connect('redis', 6379);
        $redis->publish('updates', json_encode(['message' => $message, 'targetId' => $targetId]));

        echo json_encode(['status' => 'Message sent to Redis']);
    } catch (Exception $e) {
        echo json_encode(['error' => 'Failed to process request: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid input']);
}
?>
