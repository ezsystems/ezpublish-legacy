<div class="maincontentheader">
<h1>{"Setup menu"|i18n("design/standard/class/list")}</h1>
</div>

<div class="block">
{section name=MenuItem loop=$menu_objects}

  <div class="setup_element">
    <div align="top">
      <a href={$:item.object.data_map.link.data_text|ezurl} title="{$:item.object.data_map.description.data_text |wash}">{attribute_view_gui attribute=$:item.data_map.icon}</a>  
    </div>
    <div align="bottom">
      <a href={$:item.object.data_map.link.data_text|ezurl} title="{$:item.object.data_map.description.data_text |wash}">{$:item.object.data_map.title.data_text|wash}</a>
    </div>
  </div>

  {delimiter modulo=4}
    </div>
    <div class="block">
  {/delimiter}

{/section}
</div>
