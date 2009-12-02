{* Details window. *}
{if ezpreference( 'admin_navigation_details' )}
    {include uri='design:details.tpl'}
{/if}

{* Translations window. *}
{if ezpreference( 'admin_navigation_translations' )}
    {include uri='design:translations.tpl'}
{/if}

{* Locations window. *}
{if ezpreference( 'admin_navigation_locations' )}
    {include uri='design:locations.tpl'}
{/if}

{* Relations window. *}
{if or( ezpreference( 'admin_navigation_relations' ),
                  and( is_set( $view_parameters.show_relations ), eq( $view_parameters.show_relations, 1 ) ) )}
    {include uri='design:relations.tpl'}
{/if}

{* States window. *}
{if eq( ezpreference( 'admin_navigation_states' ), 1)}
    {include uri='design:states.tpl'}
{/if}

{* Children window.*}
{if $node.object.content_class.is_container}
    {include uri='design:children.tpl'}
{else}
    {include uri='design:no_children.tpl'}
{/if}
