
<div class="block">
<label>{"Default name"|i18n("design/standard/class/datatype")}</label><div class="labelbreak"></div>
<input class="halfbox" type="text" name="ContentClass_ezmatrix_default_name_{$class_attribute.id}" value="{$class_attribute.data_text1|wash}" size="30" maxlength="60" /><br/>

<label>{"Default number of rows"|i18n("design/standard/class/datatype")}</label><div class="labelbreak"></div>
<input type="text" name="ContentClass_ezmatrix_default_num_rows_{$class_attribute.id}" value="{$class_attribute.data_int1|wash}"  size="8" maxlength="20" />

</div>

{section name=ColumnList loop=$class_attribute.content.columns sequence=array(bglight,bgdark)}

<div class="block">

<div class="element">
<label>{"Matrix Column"|i18n("design/standard/class/datatype")}</label>
<div class="labelbreak"></div>
<input class="halfbox" type="text" name="ContentClass_data_ezmatrix_column_name_{$class_attribute.id}[]" value="{$ColumnList:item.name|wash}" size="10" maxlength="255" />
</div>
<div class="element">
<label>{"Identifier"|i18n("design/standard/class/datatype")}</label><div class="labelbreak"></div>
<input type="text" name="ContentClass_data_ezmatrix_column_id_{$class_attribute.id}[]" value="{$ColumnList:item.identifier|wash}" size="10" maxlength="255" />
</div>
<div class="element">
<input type="checkbox" name="ContentClass_data_ezmatrix_column_remove_{$class_attribute.id}[]" value="{$ColumnList:index}" />
</div>
<div class="break"></div>
</div>

{/section}


<div class="buttonblock">
<input class="button" type="submit" name="CustomActionButton[{$class_attribute.id}_new_ezmatrix_column]" value="{'New Column'|i18n('design/standard/class/datatype')}" />
<input class="button" type="submit" name="CustomActionButton[{$class_attribute.id}_remove_selected]" value="{'Remove Selected'|i18n('design/standard/class/datatype')}" />
</div>
