{let b=10 data='test'}

{cache-block expiry=10 keys=$data}
The magic number is: '
{let a=20}
{sum(1,$a,3)}
{/let}
{/cache-block}'

{cache-block keys=array($b,5)}
Second magic number is: '
{let a=15}
{sum(1,$a,2)}
{/let}
{/cache-block}'
Sum: '
{cache-block}
{sum(1,$b,3)}
{/cache-block}'

{/let}
