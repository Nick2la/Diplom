<?php
// submit.php – обработчик заявки, возвращает JSON

$config = include __DIR__ . '/config.php';
define('WEBHOOK_URL', $config['webhook_url']);
define('RESPONSIBLE_ID', (int)$config['responsible']);

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Метод не поддерживается']);
    exit;
}

$name        = trim($_POST['name'] ?? '');
$subject     = trim($_POST['subject'] ?? '');
$description = trim($_POST['description'] ?? '');

if (empty($name) || empty($subject) || empty($description)) {
    echo json_encode(['success' => false, 'error' => 'Все поля обязательны для заполнения']);
    exit;
}

function callApi(string $method, array $params = [])
{
    $url = WEBHOOK_URL . $method . '.json';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    if ($error) {
        throw new RuntimeException('Ошибка соединения: ' . $error);
    }
    $data = json_decode($response, true);
    if (isset($data['error'])) {
        throw new RuntimeException('API error: ' . $data['error'] . ' - ' . ($data['error_description'] ?? ''));
    }
    return $data['result'] ?? $data;
}

try {
    $result = callApi('crm.lead.add', [
        'FIELDS' => [
            'TITLE'           => $subject,
            'COMMENTS'        => "Заявитель: {$name}\n\n" . $description,
            'ASSIGNED_BY_ID'  => RESPONSIBLE_ID,
            'SOURCE_ID'       => 'WEB',
            'STATUS_ID'       => 'NEW',
        ],
    ]);

    $leadId = is_array($result) ? ($result['ID'] ?? 'неизвестно') : $result;
    echo json_encode(['success' => true, 'lead_id' => $leadId]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}