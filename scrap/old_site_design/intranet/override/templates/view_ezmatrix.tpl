{let matrix=$attribute.content}

<table>
<tr>
{section name=Rows loop=$matrix.rows.sequential}
<td>
errrrrrrrrr
{section name=Columns loop=$Rows:item.columns}
{$Rows:Columns:item|wash(xhtml)}
{delimiter}
</td>
<td>
{/delimiter}
{/section}
</td>
{delimiter}
</tr>
<tr>
{/delimiter}
{/section}
</tr>
</table>

{/let}
