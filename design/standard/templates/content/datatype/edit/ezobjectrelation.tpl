{switch name=sw match=$attribute.content}
   {case match=0}
   <p>No relation</p>
   {/case}
   {case}
   <p>{$attribute.content.name}</p>
   {/case}
{/switch}
<input type="hidden" name="ContentObjectAttribute_data_object_relation_id_{$attribute.data_int}" value="{$attribute.data_int}" />
<input class="button" type="submit" name="BrowseObjectButton" value="{'Find object'|i18n}" />
<input type="hidden" name="CustomActionButton[{$attribute.id}_set_object_relation]" value="{$attribute.id}" />
