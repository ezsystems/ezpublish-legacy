{* Window controls. *}
<div class="menu-block{if fetch( content, translation_list )|count|eq( 1 )} notranslations{/if}">

{def $translations_count=count($node.object.available_languages)
    $li_width="" }
{def $can_create_languages=fetch( content, prioritized_languages )}
{if le($can_create_languages, 1) }
    {set $li_width="_20"}
{/if}

<ul>
    {* Content preview. *}
    {if ezpreference( 'admin_navigation_content' )}
    <li class="enabled {$li_width}">
        <a href={'/user/preferences/set/admin_navigation_content/0'|ezurl} title="{'Hide preview of content.'|i18n( 'design/admin/node/view/full' )}">{'Preview'|i18n( 'design/admin/node/view/full' )}</a>
    </li>
    {else}
    <li class="disabled {$li_width}">
        <a href={'/user/preferences/set/admin_navigation_content/1'|ezurl} title="{'Show preview of content.'|i18n( 'design/admin/node/view/full' )}">{'Preview'|i18n( 'design/admin/node/view/full' )}</a>
    </li>
    {/if}

    {* Details. *}
    {if ezpreference( 'admin_navigation_details' )}
    <li class="enabled {$li_width}">
        <a href={'/user/preferences/set/admin_navigation_details/0'|ezurl} title="{'Hide details.'|i18n( 'design/admin/node/view/full' )}">{'Details'|i18n( 'design/admin/node/view/full' )}</a>
    </li>
    {else}
    <li class="disabled {$li_width}">
        <a href={'/user/preferences/set/admin_navigation_details/1'|ezurl} title="{'Show details.'|i18n( 'design/admin/node/view/full' )}">{'Details'|i18n( 'design/admin/node/view/full' )}</a>
    </li>
    {/if}

    {if fetch( content, translation_list )|count|gt( 1 )}
    {* Translations. *}
    {if gt($can_create_languages, 1) }
    {if ezpreference( 'admin_navigation_translations' )}
    <li class="enabled {$li_width}">
        <a href={'/user/preferences/set/admin_navigation_translations/0'|ezurl} title="{'Hide available translations.'|i18n( 'design/admin/node/view/full' )}">{'Translations'|i18n( 'design/admin/node/view/full' )}</a>
    </li>
    {else}
    <li class="disabled {$li_width}">
            <a href={'/user/preferences/set/admin_navigation_translations/1'|ezurl} title="{'Show available translations.'|i18n( 'design/admin/node/view/full' )}">{'Translations'|i18n( 'design/admin/node/view/full' )}</a>
    </li>
    {/if}
    {/if}
    {/if}

    {* Locations. *}
    {if ezpreference( 'admin_navigation_locations' )}
    <li class="enabled {$li_width}">
        <a href={'/user/preferences/set/admin_navigation_locations/0'|ezurl} title="{'Hide location overview.'|i18n( 'design/admin/node/view/full' )}">{'Locations'|i18n( 'design/admin/node/view/full' )}</a>
    </li>
    {else}
    <li class="disabled {$li_width}">
        <a href={'/user/preferences/set/admin_navigation_locations/1'|ezurl} title="{'Show location overview.'|i18n( 'design/admin/node/view/full' )}">{'Locations'|i18n( 'design/admin/node/view/full' )}</a>
    </li>
    {/if}

    {* Relations. *}
    {if ezpreference( 'admin_navigation_relations' )}
    <li class="enabled {$li_width}">
        <a href={'/user/preferences/set/admin_navigation_relations/0'|ezurl} title="{'Hide object relation overview.'|i18n( 'design/admin/node/view/full' )}">{'Relations'|i18n( 'design/admin/node/view/full' )}</a>
    </li>
    {else}
    <li class="disabled {$li_width}">
        <a href={'/user/preferences/set/admin_navigation_relations/1'|ezurl} title="{'Show object relation overview.'|i18n( 'design/admin/node/view/full' )}">{'Relations'|i18n( 'design/admin/node/view/full' )}</a>
    </li>
    {/if}
    
    {* State assignment. *}
    {if eq( ezpreference( 'admin_navigation_states' ), 1 )}
    <li class="enabled {$li_width}">
        <a href={'/user/preferences/set/admin_navigation_states/0'|ezurl} title="{'Hide state assignment widget.'|i18n( 'design/admin/node/view/full' )}">{'Object states'|i18n( 'design/admin/node/view/full' )}</a>
    </li>
    {else}
    <li class="disabled {$li_width}">
        <a href={'/user/preferences/set/admin_navigation_states/1'|ezurl} title="{'Show state assignment widget.'|i18n( 'design/admin/node/view/full' )}">{'Object states'|i18n( 'design/admin/node/view/full' )}</a>
    </li>
    {/if}    
    
</ul>

<div class="break"></div>

{undef $li_width $translations_count}

</div>
