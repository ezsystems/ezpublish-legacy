FOO
{let test=73}
{$test}
{include name=navigator test=$test uri="tests/eztemplate/functions/included.tpl.tst"}
{$test}
{/let}
FOO
