<?php
return [
    'version' => json_decode(file_get_contents(base_path('package.json')), true)['version'] ?? 'dev',
];