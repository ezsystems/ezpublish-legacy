FOO
{let test=73}

test#1={$test}

{include name=navigator test=$test uri="tests/eztemplate/functions/included.tpl.tst"}

test#3={$test}

{/let}
FOO
