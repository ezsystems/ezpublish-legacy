{def $base = ezini('eZJSCore', 'LocalScriptBasePath', 'ezjscore.ini')}

{ezscript_require( 'ezjsc::yui2' )}
{ezcss_require( concat( '/', $base.yui2, 'calendar/assets/calendar.css' ) )}

<script type="text/javascript">
(function() {ldelim}
    YUILoader.addModule({ldelim}
        name: 'datepicker',
        type: 'js',
        fullpath: '{"javascript/ezdatepicker.js"|ezdesign( 'no' )}',
        requires: ["calendar"],
        after: ["calendar"],
        skinnable: false
    {rdelim});

    YUILoader.require(["datepicker"]);

    // Load the files using the insert() method.
    var options = [];
    YUILoader.insert(options, "js");
{rdelim})();
</script>

{default attribute_base=ContentObjectAttribute}
<div class="block">
<div class="date">

<div class="element">
<label>{'Year'|i18n( 'design/admin/content/datatype' )}:</label>
<input id="ezcoa-{if ne( $attribute_base, 'ContentObjectAttribute' )}{$attribute_base}-{/if}{$attribute.contentclassattribute_id}_{$attribute.contentclass_attribute_identifier}_year" class="ezcc-{$attribute.object.content_class.identifier} ezcca-{$attribute.object.content_class.identifier}_{$attribute.contentclass_attribute_identifier}" type="text" name="{$attribute_base}_date_year_{$attribute.id}" size="5" value="{section show=$attribute.content.is_valid}{$attribute.content.year}{/section}" />
</div>

<div class="element">
<label>{'Month'|i18n( 'design/admin/content/datatype' )}:</label>
<input id="ezcoa-{if ne( $attribute_base, 'ContentObjectAttribute' )}{$attribute_base}-{/if}{$attribute.contentclassattribute_id}_{$attribute.contentclass_attribute_identifier}_month" class="ezcc-{$attribute.object.content_class.identifier} ezcca-{$attribute.object.content_class.identifier}_{$attribute.contentclass_attribute_identifier}" type="text" name="{$attribute_base}_date_month_{$attribute.id}" size="3" value="{section show=$attribute.content.is_valid}{$attribute.content.month}{/section}" />
</div>

<div class="element">
<label>{'Day'|i18n( 'design/admin/content/datatype' )}:</label>
<input id="ezcoa-{if ne( $attribute_base, 'ContentObjectAttribute' )}{$attribute_base}-{/if}{$attribute.contentclassattribute_id}_{$attribute.contentclass_attribute_identifier}_day" class="ezcc-{$attribute.object.content_class.identifier} ezcca-{$attribute.object.content_class.identifier}_{$attribute.contentclass_attribute_identifier}" type="text" name="{$attribute_base}_date_day_{$attribute.id}" size="3" value="{section show=$attribute.content.is_valid}{$attribute.content.day}{/section}" />
</div>
<div class="element">
<img class="datepicker-icon" src={"calendar_icon.png"|ezimage} id="{$attribute_base}_date_cal_{$attribute.id}" width="24" height="28" onclick="showDatePicker( '{$attribute_base}', '{$attribute.id}', 'date' );" style="cursor: pointer;" />
<div id="{$attribute_base}_date_cal_container_{$attribute.id}" style="display: none; position: absolute;"></div>
&nbsp;
&nbsp;
&nbsp;
&nbsp;
</div>

<div class="break"></div>
</div>
</div>
{/default}