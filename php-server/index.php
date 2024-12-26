<?php
require_once 'db.php'; // Inclui o arquivo de conexão com o banco de dados

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['message']) && isset($data['targetId'])) {
    $message = $data['message'];
    $targetId = $data['targetId'];

    try {
        // Salvar no banco de dados
        $stmt = $pdo->prepare("INSERT INTO messages (message, target_id) VALUES (:message, :targetId)");
        $stmt->bindParam(':message', $message, PDO::PARAM_STR);
        $stmt->bindParam(':targetId', $targetId, PDO::PARAM_INT);
        $stmt->execute();
        error_log("Message saved to database: message=$message, targetId=$targetId");

        // Publicar no Redis
        $redis = new Redis();
        if (!$redis->connect('redis', 6379)) {
            throw new Exception('Failed to connect to Redis');
        }
        $payload = json_encode(['message' => $message, 'targetId' => (string) $targetId]);
        $redis->publish('updates', $payload);
        error_log("Published to Redis: $payload");

        echo json_encode(['status' => 'Message sent to Redis']);
    } catch (Exception $e) {
        error_log("Error: " . $e->getMessage());
        echo json_encode(['error' => 'Failed to process request: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid input']);
}
?>
