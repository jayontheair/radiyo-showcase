<?php
require __DIR__.'/voice.php';
try {
    $result = synthesize('Welcome to our fictional radio station. Here is your weather bulletin.', ['api_key'=>getenv('ELEVENLABS_API_KEY') ?: '', 'voice_id'=>getenv('ELEVENLABS_VOICE_ID') ?: ''], 'curlTransport');
    if (!$result['ok']) throw new RuntimeException('Provider error: '.$result['status']);
    if (file_put_contents('sample.mp3',$result['audio']) === false) throw new RuntimeException('Could not save audio');
    echo "Saved sample.mp3\n";
} catch (Throwable $e) { fwrite(STDERR,"Voice demo failed: ".$e->getMessage()."\n"); exit(1); }
