{sequence name=Seq loop=array(a,b,c)}

<table>
<tr><th>Sequence value</th><th>Section item</th></tr>
{section name=Loop1 loop=array(1,2,3,4,5,6)}

<tr><td>{$Seq:item}</td><td>{$Loop1:item}</td></tr>

{* Next sequence *}
{sequence name=Seq}
{/section}
</table>
