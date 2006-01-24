{def $i=5}
{while $i sequence array(light,dark) as $seq}
{delimiter} [{$seq}-{$i}] {/delimiter}
{$seq}-{$i}
{set $i=dec( $i )}
{/while}
