 Persistence<br/> 
{section name=Persistence loop=$#persistence_list}
 {section name=Item loop=$:item}
  {section show=is_array($:item)}
   {section name=ArrayElement loop=$:item}
{*    input type="hidden" name="P_{$Persistence:key}-{$Persistence:Item:key}[]" value="{$:item}" <br/>  *}
    <input type="hidden" name="P_{$Persistence:key}-{$Persistence:Item:key}[]" value="{$:item}" />
   {/section}
  {section-else}
{*   input type="hidden" name="P_{$Persistence:key}-{$:key}" value="{$:item}" <br/>  *}
   <input type="hidden" name="P_{$Persistence:key}-{$:key}" value="{$:item}" />
  {/section}
 {/section}
{/section}