{section show=$attribute.content}
   <p class="box">{content_view_gui view=text_linked content_object=$attribute.content}</p>
{section-else}
   <p class="box">{"No relation"|i18n("design/standard/content/datatype")}</p>
{/section}
<input type="hidden" name="ContentObjectAttribute_data_object_relation_id_{$attribute.data_int}" value="{$attribute.data_int}" />
<input class="button" type="submit" name="BrowseObjectButton_{$attribute.id}" value="{'Find object'|i18n('design/standard/content/datatype')}" />
<input type="hidden" name="CustomActionButton[{$attribute.id}_set_object_relation]" value="{$attribute.id}" />
