<?php
declare(strict_types=1);
// Fictional in-memory records replace the production database query.
function drawWinner(array $entrants, ?callable $rng=null): array {
    $pool=[]; $total=0; $seen=[];
    foreach($entrants as $e){
        if (!isset($e['id'],$e['entries']) || !is_int($e['entries']) || $e['entries']<1 || $e['entries']>100000 || isset($seen[$e['id']])) throw new InvalidArgumentException('Invalid entrant');
        $seen[$e['id']]=true;
        if (!$e['eligible'] || $e['already_won'] || $e['recent_win']) continue;
        $pool[]=$e; $total+=$e['entries'];
    }
    if (!$total) return ['winner'=>null,'tickets'=>0,'pool'=>0];
    $ticket=($rng ?? 'random_int')(1,$total);
    if (!is_int($ticket)||$ticket<1||$ticket>$total) throw new RuntimeException('Invalid random ticket');
    foreach($pool as $e){$ticket-=$e['entries'];if($ticket<=0)return ['winner'=>$e['id'],'tickets'=>$total,'pool'=>count($pool)];}
    throw new LogicException('Unreachable draw state');
}
