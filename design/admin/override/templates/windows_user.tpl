{* Details window. *}
{if ezpreference( 'admin_navigation_details'  )}
    {include uri='design:details.tpl'}
{/if}

{* Translations window. *}
{if ezpreference( 'admin_navigation_translations'  )}
    {include uri='design:translations.tpl'}
{/if}

{* Locations window. *}
{if ezpreference( 'admin_navigation_locations'  )}
    {include uri='design:locations.tpl'}
{/if}

{* Relations window. *}
{if ezpreference( 'admin_navigation_relations'  )}
    {include uri='design:relations.tpl'}
{/if}

{* Member of roles window. *}
{if ezpreference( 'admin_navigation_roles'  )}
    {include uri='design:roles.tpl'}
{/if}

{* Policy list window. *}
{if ezpreference( 'admin_navigation_policies'  )}
    {include uri='design:policies.tpl'}
{/if}

{* Children window.*}
{if $node.object.content_class.is_container}
    {include uri='design:children.tpl'}
{else}
    {include uri='design:no_children.tpl'}
{/if}
