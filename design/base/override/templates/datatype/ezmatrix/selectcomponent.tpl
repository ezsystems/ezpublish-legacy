<table>
<tr>
<th>
{section name=ColumnNames loop=$matrix.columns.sequential}
{$ColumnNames:item.name}
{delimiter}
</th>
<th>
{/delimiter}
{/section}
</th>
</tr>
<tr>
    {let default_matrix_field=$selectbox_content[0]
         selected_is_set=0
         selectbox_count=count($selectbox_content)}
    {section var=row loop=$matrix.rows.sequential}
    <td>
        {section var=column loop=$row.item.columns}
            {switch match=$column.index}
            {case match=0}
                <select name="ContentObjectAttribute_ezmatrix_cell_{$attribute.id}[]">
                {section var=selectbox_element loop=$:selectbox_content}
                    {if and(eq( first_set( $column.item|wash(xhtml),$default_matrix_field ) , $selectbox_element.item), eq($selected_is_set, 0))}
                        <option selected value="{$selectbox_element.item}">{$selectbox_element.item}</option>
            {set default_matrix_field=$selectbox_element.index|inc}
            {set default_matrix_field=$selectbox_content[mod($default_matrix_field,$selectbox_count)]}
            {set selected_is_set=1}
                    {else}
                        <option value="{$selectbox_element.item}">{$selectbox_element.item}</option>
                    {/if}
                {/section}
                </select>
            {/case}
            {case}
                {if eq($inputType,textarea)}
                    <textarea name="ContentObjectAttribute_ezmatrix_cell_{$attribute.id}[]" rows="4">{$column.item|wash(xhtml)}</textarea>
                {else}
                    <input type="text" name="ContentObjectAttribute_ezmatrix_cell_{$attribute.id}[]" value="{$column.item|wash(xhtml)}" />
                {/if}
            {/case}
            {/switch}

            {delimiter}
            </td>
            <td>
            {/delimiter}
        {/section}
    </td>
    <td>
    <input type="checkbox" name="ContentObjectAttribute_data_matrix_remove_{$attribute.id}[]" value="{$row.index}" /><br />
    </td>
    {delimiter}
    </tr>
    <tr>
    {/delimiter}
    {set selected_is_set=0}

    {/section}
    {/let}
</tr>
</table>
