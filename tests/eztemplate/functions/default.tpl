{default a="some value"}

$a should contain "{$a}"

{let b="another value"}

{default b="the third place"}

$b should contain "{$b}"

{/default}

{/let}

{/default}
