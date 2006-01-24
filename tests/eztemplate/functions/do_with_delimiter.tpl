{def $i=5}
{do}
{delimiter} [{$seq}-{$i}] {/delimiter}
{$seq}-{$i}
{set $i=dec( $i )}
{/do while $i sequence array(light,dark) as $seq}
