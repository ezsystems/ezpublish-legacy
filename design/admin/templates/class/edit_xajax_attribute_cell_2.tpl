<input type="hidden" name="ContentAttribute_id[]" value="{$attribute.id}" />
<input type="hidden" name="ContentAttribute_position[]" value="{$attribute.placement}" />


{* Attribute name. *}
<div class="block">
<label>{'Name'|i18n( 'design/admin/class/edit' )}:</label>
<input class="box" type="text" name="ContentAttribute_name[]" value="{$attribute.name|wash}" title="{'Use this field to set the informal name of the attribute. This field can contain whitespaces and special characters.'|i18n( 'design/admin/class/edit' )}" />
</div>

{* Attribute identifier. *}
<div class="block">
<label>{'Identifier'|i18n( 'design/admin/class/edit' )}:</label>
<input class="box" type="text" name="ContentAttribute_identifier[]" value="{$attribute.identifier}" title="{'Use this field to set the internal name of the attribute. The identifier will be used in templates and in PHP code. Allowed characters: letters, numbers and underscore.'|i18n( 'design/admin/class/edit' )}" />
</div>

<!-- Attribute input End -->

<!-- Attribute flags Start -->
<div class="block inline">

{* Required. *}
<label>
<input type="checkbox" name="ContentAttribute_is_required_checked[]" value="{$attribute.id}"  {section show=$attribute.is_required}checked="checked"{/section} title="{'Use this checkbox to control if the user should be forced to enter information into the attribute.'|i18n( 'design/admin/class/edit' )}" />
{'Required'|i18n( 'design/admin/class/edit' )}
</label>

{* Searchable. *}

<label>
{section show=$attribute.data_type.is_indexable}
<input type="checkbox" name="ContentAttribute_is_searchable_checked[]" value="{$attribute.id}"  {section show=$attribute.is_searchable}checked="checked"{/section} title="{'Use this checkbox to control if the contents of the attribute should be indexed by the search engine.'|i18n( 'design/admin/class/edit' )}" />
{section-else}
<input type="checkbox" name="" value="" disabled="disabled" title="{'The <%datatype_name> datatype does not support search indexing.'|i18n( 'design/admin/class/edit',, hash( '%datatype_name', $attribute.data_type.information.name ) )|wash}" />
{/section}
{'Searchable'|i18n( 'design/admin/class/edit' )}
</label>

{* Information collector. *}
<label>
{section show=$attribute.data_type.is_information_collector}
<input type="checkbox" name="ContentAttribute_is_information_collector_checked[]" value="{$attribute.id}"  {section show=$attribute.is_information_collector}checked="checked"{/section} title="{'Use this checkbox to control if the attribute should collect input from users.'|i18n( 'design/admin/class/edit' )}" />
{section-else}
<input type="checkbox" name="" value="" disabled="disabled" title="{'The <%datatype_name> datatype can not be used as an information collector.'|i18n( 'design/admin/class/edit',, hash( '%datatype_name', $attribute.data_type.information.name ) )|wash}" />
{/section}
{'Information collector'|i18n( 'design/admin/class/edit' )}
</label>


{* Disable translation. *}
<label>
<input type="checkbox" name="ContentAttribute_can_translate_checked[]" value="{$attribute.id}" {section show=$attribute.can_translate|eq(0)}checked="checked"{/section} title="{'Use this checkbox for attributes that contain non-translatable content.'|i18n( 'design/admin/class/edit' )}" />
{'Disable translation'|i18n( 'design/admin/class/edit' )}
</label>

</div>

{class_attribute_edit_gui class_attribute=$attribute}
