{let lookup1=hash( 1, 'One',
                   2, 'Two',
                   3, 'Three',
                   4, 'Four',
                   5, 'Five',
                   6, 'Six',
                   7, 'Seven',
                   8, 'Eight',
                   9, 'Nine',
                   10, 'Ten' )
     lookup2=hash( 1, 'I',
                   2, 'II',
                   3, 'III',
                   4, 'IV',
                   5, 'V',
                   6, 'VI',
                   7, 'VII',
                   8, 'VIII',
                   9, 'IX',
                   10, 'X' )
     lookups=array( $lookup1, $lookup2 )}
{section var=lookup loop=$lookups}
{section var=number loop=array( 2, 3, 5, 10 )}
{* This first test checks that two 'proxy' objects can be used in an expression.
   It will fail if the template system doesn't extract the values the proxys point to *}
$lookup[$number]($number)               ={$lookup[$number]}({$number})
{* This second test checks that two 'proxy' objects with explicit value extraction can be used in an expression.
   It will fail if the template system extracts the values before the '.item' is called.  *}
$lookup.item[$number.item]($number.item)={$lookup.item[$number.item]}({$number.item})

{/section}
{/section}
{/let}
