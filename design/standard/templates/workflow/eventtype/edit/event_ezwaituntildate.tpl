<div class="element">

<input type="checkbox" name="WorkflowEvent_data_waituntildate_modifydate_{$event.id}[]" value="1" {section show=$event.data_int1}checked="checked"{/section} /> {'Modify publish date'|i18n('design/standard/workflow/eventtype/edit')}

<table class="list">
<th> Class name </th>
<th> ClassAttribute name </th>
{section name=Entries loop=$event.content.entry_list sequence=array(bglight,bgdark)}
<tr>
<td class="{$Entries:sequence}">
{$Entries:item.class_name}({$Entries:item.contentclass_id}) 
</td>
<td class="{$Entries:sequence}">
{$Entries:item.classattribute_name}({$Entries:item.contentclass_attribute_id})
</td>
<td class="{$Entries:sequence}">
<input type="checkbox" name="WorkflowEvent_data_waituntildate_remove_{$event.id}[]" value="{$Entries:item.id}" /><br/>
</td>
</tr>
{/section}
</table>

<div class="buttonblock">
<input class="button" type="submit" name="CustomActionButton[{$event.id}_new_classelement]" value="{'New entry'|i18n('design/standard/workflow/eventtype/edit')}" />
<input class="button" type="submit" name="CustomActionButton[{$event.id}_remove_selected]" value="{'Remove selected'|i18n('design/standard/workflow/eventtype/edit')}" />
<input class="button" type="submit" name="CustomActionButton[{$event.id}_load_class_attribute_list]" value="{'Load attributes'|i18n('design/standard/workflow/eventtype/edit')}" />
</div>

<div class="element">

{let possibleClasses=$event.workflow_type.contentclass_list}
    <label>{"Class"|i18n("design/standard/workflow/eventtype/edit")}</label><div class="labelbreak"></div>
     <select name="WorkflowEvent_event_ezwaituntildate_class_{$event.id}[]" size="5">
     {section name=HasClasses loop=$possibleClasses}
     <option value="{$HasClasses:item.id}">{$HasClasses:item.id}-{$HasClasses:item.name}</option>
     {/section}   
     </select>
{/let} 
</div>
<div class="element">
{section show=$event.workflow_type.has_class_attributes|eq(1)}
{let possibleClassAttributes=$event.workflow_type.contentclassattribute_list}
    <label>{"Class Attributes:"|i18n("design/standard/workflow/eventtype/edit")}</label><div class="labelbreak"></div>
     <select name="WorkflowEvent_event_ezwaituntildate_classattribute_{$event.id}[]" size="5">
     {section name=HasClassAttributes loop=$possibleClassAttributes}
     <option value="{$HasClassAttributes:item.id}">{$HasClassAttributes:item.id}-{$HasClassAttributes:item.name}</option>
     {/section}
    </select>
{/let} 
{section-else}
  Load attributes
{section}
</div>

</div>

