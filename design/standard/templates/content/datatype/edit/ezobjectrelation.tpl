{default attribute_base=ContentObjectAttribute}
{section show=$attribute.content}
   <p class="box">{content_view_gui view=text_linked content_object=$attribute.content}</p>
{section-else}
   <p class="box">{"No relation"|i18n("design/standard/content/datatype")}</p>
{/section}
<input type="hidden" name="{$attribute_base}_data_object_relation_id_{$attribute.data_int}" value="{$attribute.data_int}" />
{section show=$attribute.content}
<input class="button" type="submit" name="BrowseObjectButton_{$attribute.id}" value="{'Replace object'|i18n('design/standard/content/datatype')}" />
<input class="button" type="submit" name="RemoveObjectButton_{$attribute.id}" value="{'Remove object'|i18n('design/standard/content/datatype')}" />
{section-else}
<input class="button" type="submit" name="BrowseObjectButton_{$attribute.id}" value="{'Find object'|i18n('design/standard/content/datatype')}" />
{/section}
<input type="hidden" name="CustomActionButton[{$attribute.id}_set_object_relation]" value="{$attribute.id}" />
{/default}
