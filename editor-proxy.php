<?php
// Fetch CSS from Vite dev server
$vite_url = 'https://wolz.local:5173/src/styles/gutenberg-editor-bulma.scss?direct';

$response = file_get_contents($vite_url, false, stream_context_create([
    'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
]));

header('Content-Type: text/css');
echo $response;