-----------

{let myvar=9}
{switch match=$myvar}
{case match=9}
===========
    Match 9
===========
{/case}
{case match=8}
===========
    Match 8
===========
{/case}
{case}
===========
  Match Default
===========
{/case}
{/switch}
{/let}

-----------

--------------------------

{let myarr=array(1,2,3,4,5)
     switcherarray=array(5,6,7,8,9)}

{section loop=$switcherarray var=switcher}

=====
switch test in let loop: {$switcher.number}
=====
{section show=$switcher|gt(7)}
========
   - switcher is greater than 7 (to be precise: {$switcher})
========
{switch match=$switcher}
{case match=9}
===========
      - switcher match 9
===========
{/case}
{case match=8}
===========
      - switcher match 8( going to check loop )
===========
================ List
{section loop=$myarr var=element}

                - {$element}
{/section}

================
{/case}
{case}{*default*}
===========
     - Nothing matched
===========
  {/case}
  {/switch}
{section-else}{*show*}
========
   - switcher={$switcher}
========
{/section}{*show*}
{/section}{*loop*}
{/let}