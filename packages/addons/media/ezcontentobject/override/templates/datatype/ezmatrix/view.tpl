{let matrix=$attribute.content}

<table>
<tr>
{section name=Rows loop=$matrix.rows.sequential}
    <td>
        {section name=Columns loop=$Rows:item.columns}
            {section show=0|eq($Rows:Columns:index)}
               {switch match=$attribute.contentclass_attribute_identifier}
               {case match='contact_information'}
                     <em>{$Rows:Columns:item}:</em>
               {/case}
               {case match='company_address'}
                     <em>{$Rows:Columns:item}:</em>
               {/case}
               {case}
               {/case}
               {/switch}
            {section-else}
               {$Rows:Columns:item|wash(xhtml)|autolink|nl2br}
            {/section}

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
