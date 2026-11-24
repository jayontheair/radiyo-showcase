<?php
require __DIR__.'/../examples/voice.php';
require __DIR__.'/../examples/filter.php';
require __DIR__.'/../examples/draw.php';
require __DIR__.'/../examples/pace.php';
$n=0;
function check(bool $ok,string $label):void {global $n;if(!$ok)throw new RuntimeException($label);echo 'PASS '.$label.PHP_EOL;$n++;}
function rejects(callable $f,string $label):void {try{$f();}catch(InvalidArgumentException $e){check(true,$label);return;}check(false,$label);}
$cfg=['api_key'=>'fictional-test-key','voice_id'=>'fictional-voice'];
$mock=function($url,$headers,$body){check(str_starts_with($url,'https://api.elevenlabs.io/'),'provider URL');check(json_decode($body,true)['text']==='Demo','script payload');return [200,'mock audio','audio/mpeg'];};
check(synthesize('Demo',$cfg,$mock)['ok'],'voice success');
check(!synthesize('Demo',$cfg,fn()=>[401,'','application/json'])['ok'],'voice error');
check(!synthesize('Demo',$cfg,fn()=>[200,'','audio/mpeg'])['ok'],'empty audio rejected');
rejects(fn()=>synthesize('',$cfg,$mock),'empty script rejected');
rejects(fn()=>synthesize('Demo',[],$mock),'missing credentials rejected');
$f=['groups'=>[['conditions'=>[['field'=>'state','op'=>'is','value'=>"QLD' OR 1=1 --"]]]]];
$r=compileFilter($f);check($r['sql']==='((c.state = ?))' && $r['params'] === ["QLD' OR 1=1 --"],'SQL value binding');
$f['groups'][0]['conditions'][0]['field']='malicious';rejects(fn()=>compileFilter($f),'unknown field rejected');
$e=[['id'=>1,'entries'=>1,'eligible'=>true,'already_won'=>false,'recent_win'=>false],['id'=>2,'entries'=>3,'eligible'=>true,'already_won'=>false,'recent_win'=>false],['id'=>3,'entries'=>100,'eligible'=>true,'already_won'=>false,'recent_win'=>true]];
check(drawWinner($e,fn()=>1)['winner']===1,'first ticket');check(drawWinner($e,fn()=>4)['winner']===2,'weighted last ticket');check(drawWinner($e)['tickets']===4,'recent winners excluded');check(drawWinner([])['winner']===null,'empty pool');
check(campaignPace(0.5,0.5,false)[0]==='On pace','campaign on pace');check(campaignPace(1,0.5,false)[0]==='Goal hit!','goal hit');check(campaignPace(0.8,1,true)[0]==='So close','ended campaign');
echo "$n checks passed\n";
