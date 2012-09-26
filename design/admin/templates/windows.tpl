{* (Pre)view window *}
<div id="node-tab-view-content" class="tab-content{if $node_tab_index|ne('view')} hide{else} selected{/if}">
    {include uri='design:preview.tpl'}
<div class="break"></div>
</div>

{* Details window *}
<div id="node-tab-details-content" class="tab-content{if $node_tab_index|ne('details')} hide{else} selected{/if}">
    {include uri='design:details.tpl'}
<div class="break"></div>
</div>

{* Translations window *}
<div id="node-tab-translations-content" class="tab-content{if $node_tab_index|ne('translations')} hide{else} selected{/if}">
    {include uri='design:translations.tpl'}
<div class="break"></div>
</div>

{* Locations window *}
<div id="node-tab-locations-content" class="tab-content{if $node_tab_index|ne('locations')} hide{else} selected{/if}">
    {include uri='design:locations.tpl'}
<div class="break"></div>
</div>

{* Relations window *}
<div id="node-tab-relations-content" class="tab-content{if $node_tab_index|ne('relations')} hide{else} selected{/if}">
    {include uri='design:relations.tpl'}
<div class="break"></div>
</div>

{* Published ordering window *}
<div id="node-tab-ordering-content" class="tab-content{if $node_tab_index|ne('ordering')} hide{else} selected{/if}">
    {include uri='design:ordering.tpl'}
<div class="break"></div>
</div>

{include uri='design:windows_extratabs.tpl'}
