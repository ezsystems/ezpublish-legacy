<h2>Test of different functions</h2>

<h3>Showing the left and right delimiter</h3>
Left delimiter={ldelim}, right delimiter={rdelim}

<h3>Displaying text literally</h3>
{literal}
This text is shown just as it is, this means that template tags don't have a special meaning anymore,
not event comments.

{* Attempt to create a section *}
{section loop=$array1}
{/section}

{/literal}

<br/>

<h3>Looping arrays</h3>
<table>
    <tr><th>Index</th><th>Number</th><th>Key</th><th>Item</th></tr>

{* This example shows the various template variables which are created by the section function *}
{section name=Test loop=$array1}
    <tr><td>{$Test:index}</td><td>{$Test:number}</td><td>{$Test:key}</td><td>{$Test:item}</td></tr>
{/section}
</table>

<h3>Conditional content with switching</h3>
{switch match=$match1}
  {case match=1}Matched 1{/case}
  {case match=2}Matched 2{/case}
  {case}Matched default{/case}
{/switch}

<h3>Conditional content with switching in array</h3>

{switch match=$match_id1}
  {case in=$match_arr1}
    Matched {$match} in {$match_arr1|attribute(,,show)}
  {/case}
  {case in=$match_arr2}
    Matched {$match} in {$match_arr2|attribute(,,show)}
  {/case}
  {case}
    Default match for array
  {/case}
{/switch}

<h3>Conditional content with switching in array with key</h3>

{switch match=$match_id2}
  {case key=id in=$match_assoc1}
    Matched {$match} in key 'id' for {$match_assoc1|attribute(,,show)}
  {/case}
  {case key=id in=$match_assoc2}
    Matched {$match} in key 'id' for {$match_assoc2|attribute(,,show)}
  {/case}
  {case}
    Default match for assoc
  {/case}
{/switch}

<h3>Template includes</h3>

Include uri {$uri} <br/>
{include uri=$uri}
