<?php
declare(strict_types=1);
// Simplified standalone adaptation: strictly reject invalid predicates.
function compileFilter(array $filter): array {
    $fields = ['state'=>'c.state','suburb'=>'c.suburb','postcode'=>'c.postcode'];
    $ops = ['is'=>'=','is_not'=>'<>','contains'=>'LIKE','starts_with'=>'LIKE'];
    $groups = $filter['groups'] ?? [];
    $match = $filter['match'] ?? 'all';
    if (!in_array($match,['all','any'],true) || !is_array($groups) || count($groups)<1 || count($groups)>10) throw new InvalidArgumentException('Invalid groups');
    $fragments=[]; $params=[];
    foreach($groups as $group) {
        $connector=$group['connector'] ?? 'and'; $conditions=$group['conditions'] ?? [];
        if (!in_array($connector,['and','or'],true) || !is_array($conditions) || count($conditions)<1 || count($conditions)>10) throw new InvalidArgumentException('Invalid conditions');
        $parts=[];
        foreach($conditions as $condition) {
            $field=$condition['field'] ?? ''; $op=$condition['op'] ?? ''; $value=$condition['value'] ?? null;
            if (!is_string($field)||!is_string($op)||!isset($fields[$field],$ops[$op])||!is_string($value)||trim($value)===''||strlen($value)>200) throw new InvalidArgumentException('Invalid predicate');
            $parts[]=$fields[$field].' '.$ops[$op].' ?';
            $params[]=match($op){'contains'=>'%'.$value.'%','starts_with'=>$value.'%',default=>$value};
        }
        $fragments[]='('.implode(' '.strtoupper($connector).' ',$parts).')';
    }
    return ['sql'=>'('.implode($match==='all'?' AND ':' OR ',$fragments).')','params'=>$params,'types'=>str_repeat('s',count($params))];
}
