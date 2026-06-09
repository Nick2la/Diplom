<?php
// config.php – основные настройки интеграции с Bitrix24
return [
    // URL входящего вебхука 
    'webhook_url'  => 'https://technicalsupport71.bitrix24.ru/rest/1/bougpnwbotunxc3w/',
    
    // ID ответственного менеджера по умолчанию 
    'responsible'  => 1,
    
    // ID группы экстранета.
    'extranet_group_id' => 0,
    
    // Путь к файлу кеша для хранения ID группы экстранета
    'cache_file'   => __DIR__ . '/extranet_group_id.txt',
];
