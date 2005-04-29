{let matrix=$attribute.content}
{switch match=$attribute.contentclass_attribute_identifier}
{case match='company_address'}
   {* {let selectbox_content=array("Postal Address", "Visitor Addres" ) }*}
    {let selectbox_content=ezini( 'MatrixComponentSettings', 'CompanyAddress', 'content.ini' )
         inputType=textarea}
    {include uri="design:datatype/ezmatrix/selectcomponent.tpl" }
    {/let}
{/case}
{case match='contact_information'}
    {let selectbox_content=false()
         inputType=text}
    {switch match=$attribute.object.class_identifier}
    {case match='company'}
        {set selectbox_content=ezini( 'MatrixComponentSettings', 'CompanyContactInfo', 'content.ini' )}
    {/case}
    {case match='person'}
        {set selectbox_content=ezini( 'MatrixComponentSettings', 'PersonContactInfo', 'content.ini' )}
    {/case}
    {/switch}
    {include uri="design:datatype/ezmatrix/selectcomponent.tpl" }
    {/let}
{/case}
{case}
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
{section name=Rows loop=$matrix.rows.sequential}
    <td>
    {section name=Columns loop=$Rows:item.columns}
    <input type="text" name="ContentObjectAttribute_ezmatrix_cell_{$attribute.id}[]" value="{$Rows:Columns:item|wash(xhtml)}" />
    {delimiter}
</td>
<td>
     {/delimiter}
    {/section}
</td>
<td>
    <input type="checkbox" name="ContentObjectAttribute_data_matrix_remove_{$attribute.id}[]" value="{$Rows:index}" /><br />
</td>
     {delimiter}
</tr>
<tr>
     {/delimiter}
     {/section}
</tr>
</table>
{/case}
{/switch}

<div class="buttonblock">
<input class="button" type="submit" name="CustomActionButton[{$attribute.id}_new_row]" value="{'New row'|i18n('design/base')}" />
<input class="button" type="submit" name="CustomActionButton[{$attribute.id}_remove_selected]" value="{'Remove Selected'|i18n('design/base')}" />
</div>

{/let}
