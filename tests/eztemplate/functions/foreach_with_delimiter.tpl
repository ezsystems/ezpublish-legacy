{foreach array( 1, 2, 3 ) as $i sequence array(one,two) as $seq}
{$i}-{$seq}
{delimiter} [{$i}-{$seq}] {/delimiter}
{/foreach}
