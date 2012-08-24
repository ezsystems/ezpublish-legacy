<script type="text/javascript">
function toggleClass( selection )
{ldelim}
    var classAttributeSelect = document.getElementById( "WorkflowEvent_event_ezwaituntildate_classattribute_{$event.id}" );
    var classAttributesHash={ldelim}
    {foreach $event.workflow_type.contentclass_list as $class}
        {delimiter},{/delimiter}
        {$class.id} : {ldelim}
        {foreach fetch( 'class', 'attribute_list', hash( 'class_id', $class.id ) ) as $attribute}
            {delimiter},{/delimiter}
            {$attribute.id} : "{$attribute.name}"
        {/foreach}
        {rdelim}
    {/foreach}
    {rdelim};
    classAttributeSelect.options.length=0;
    for ( var classAttributeId in classAttributesHash[selection.value] )
    {ldelim}
        classAttributeSelect.options.add( new Option( classAttributesHash[selection.value][classAttributeId], classAttributeId ) );
    {rdelim}
{rdelim}
</script>

{* Class *}
<div class="block">
    <label>{'Class'|i18n( 'design/admin/workflow/eventtype/edit' )}:</label>
    <select name="WorkflowEvent_event_ezwaituntildate_class_{$event.id}[]" onchange="toggleClass( this )">
    {section var=Classes loop=$event.workflow_type.contentclass_list}
        {if and( and( is_set( $selectedClass ), $selectedClass ), eq( $selectedClass, $Classes.item.id ) )}
        <option value="{$Classes.item.id}" selected=true>{$Classes.item.name|wash}</option>
        {else}
                <option value="{$Classes.item.id}">{$Classes.item.name|wash}</option>
    {/if}
    {/section}
    </select>
</div>


{* Attributes *}
<div class="block">
    {let possibleClassAttributes=$event.workflow_type.contentclassattribute_list}
    <label>{'Attribute'|i18n( 'design/admin/workflow/eventtype/edit' )}:</label>
    <select id="WorkflowEvent_event_ezwaituntildate_classattribute_{$event.id}" name="WorkflowEvent_event_ezwaituntildate_classattribute_{$event.id}[]">
    {section name=HasClassAttributes loop=$possibleClassAttributes}
        <option value="{$HasClassAttributes:item.id}">{$HasClassAttributes:item.name|wash}</option>
    {/section}
    </select>
    {/let}
    <input class="button" type="submit" name="CustomActionButton[{$event.id}_new_classelement]" value="{'Select attribute'|i18n( 'design/admin/workflow/eventtype/edit' )}" />
</div>


{* Class/attribute list *}
<div class="block">
<label>{'Class/attribute combinations (%count)'|i18n( 'design/admin/workflow/eventtype/edit',, hash( '%count', $event.content.entry_list|count ) )}:</label>
{section show=$event.content.entry_list}
<table class="list" cellspacing="0">
<tr>
<th class="tight">&nbsp;</th>
<th>{'Class'|i18n( 'design/admin/workflow/eventtype/edit' )}</th>
<th>{'Attribute'|i18n( 'design/admin/workflow/eventtype/edit' )}</th>
</tr>
{section var=Entries loop=$event.content.entry_list sequence=array( bglight, bgdark )}
<tr class="{$Entries.sequence}">
<td><input type="checkbox" name="WorkflowEvent_data_waituntildate_remove_{$event.id}[]" value="{$Entries.item.id}" /></td>
<td>{$Entries.item.class_name}</td>
<td>{$Entries.item.classattribute_name}</td>
</tr>
{/section}
</table>
{section-else}
<p>{'There are no combinations'|i18n( 'design/admin/workflow/eventtype/edit' )}</p>
{/section}
<div class="controlbar">
<input class="button" type="submit" name="CustomActionButton[{$event.id}_remove_selected]" value="{'Remove selected'|i18n( 'design/admin/workflow/eventtype/edit' )}" {if $event.content.entry_list|not}disabled="disabled"{/if} />
</div>
</div>


{* Modify publishing dates *}
<div class="block">
<label>{"Modify the objects' publishing dates"|i18n( 'design/admin/workflow/eventtype/edit' )}:</label>
<input type="checkbox" name="WorkflowEvent_data_waituntildate_modifydate_{$event.id}[]" value="1" {if $event.data_int1}checked="checked"{/if} />
</div>

