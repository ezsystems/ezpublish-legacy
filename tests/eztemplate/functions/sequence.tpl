{sequence name=Seq loop=array(a,b,c)}
{sequence name=Derick loop=array(foo,bar,baz)}

<table>
<tr><th>Sequence value</th><th>Section item</th></tr>

<tr><td>{$Seq:iteration}. {$Seq:item}</td><td>{$Derick:iteration}. {$Derick:item}</td></tr>

{sequence name=Seq}
{sequence name=Derick}
<tr><td>{$Seq:iteration}. {$Seq:item}</td><td>{$Derick:iteration}. {$Derick:item}</td></tr>

{sequence name=Seq}
<tr><td>{$Seq:iteration}. {$Seq:item}</td><td>{$Derick:iteration}. {$Derick:item}</td></tr>

{sequence name=Seq}
{sequence name=Derick}
<tr><td>{$Seq:iteration}. {$Seq:item}</td><td>{$Derick:iteration}. {$Derick:item}</td></tr>

{sequence name=Seq}
<tr><td>{$Seq:iteration}. {$Seq:item}</td><td>{$Derick:iteration}. {$Derick:item}</td></tr>

{sequence name=Seq}
{sequence name=Derick}
<tr><td>{$Seq:iteration}. {$Seq:item}</td><td>{$Derick:iteration}. {$Derick:item}</td></tr>

</table>
