<?php
declare(strict_types=1);
// Standalone adaptation of the RadiYO! provider-dispatch pattern.
function synthesize(string $text, array $config, callable $transport): array {
    $text = trim($text);
    if ($text === '' || strlen($text) > 5000) throw new InvalidArgumentException('Invalid script length');
    if (($config['provider'] ?? 'elevenlabs') !== 'elevenlabs') throw new InvalidArgumentException('Unsupported provider');
    $key = $config['api_key'] ?? '';
    $voice = $config['voice_id'] ?? '';
    if ($key === '' || $voice === '' || preg_match('/[\r\n]/', $key)) throw new InvalidArgumentException('Missing or invalid provider configuration');
    $url = 'https://api.elevenlabs.io/v1/text-to-speech/'.rawurlencode($voice).'?output_format=mp3_44100_128';
    $payload = json_encode(['text'=>$text,'model_id'=>$config['model'] ?? 'eleven_multilingual_v2','voice_settings'=>['stability'=>0.5,'similarity_boost'=>0.75]], JSON_THROW_ON_ERROR);
    [$status,$body,$mime] = $transport($url, ['xi-api-key: '.$key,'Content-Type: application/json','Accept: audio/mpeg'], $payload);
    if ($status !== 200 || !is_string($body) || $body === '' || !str_starts_with(strtolower($mime),'audio/mpeg')) return ['ok'=>false,'error'=>'Voice provider request failed','status'=>$status];
    return ['ok'=>true,'audio'=>$body,'mime'=>'audio/mpeg'];
}
function curlTransport(string $url,array $headers,string $payload): array {
    $ch = curl_init($url);
    curl_setopt_array($ch,[CURLOPT_POST=>true,CURLOPT_POSTFIELDS=>$payload,CURLOPT_HTTPHEADER=>$headers,CURLOPT_RETURNTRANSFER=>true,CURLOPT_CONNECTTIMEOUT=>10,CURLOPT_TIMEOUT=>30]);
    $body = curl_exec($ch);
    $status = (int)curl_getinfo($ch,CURLINFO_HTTP_CODE);
    $mime = (string)curl_getinfo($ch,CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    return [$status,$body,$mime];
}
