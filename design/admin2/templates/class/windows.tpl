{* Class translations window. *}
{if ezpreference( 'admin_navigation_class_translations' )}
    {include uri='design:class/translations.tpl'}
{/if}

{* Class groups window. *}
{if ezpreference( 'admin_navigation_class_groups' )}
    {include uri='design:class/groups.tpl'}
{/if}

{* Class override templates window. *}
{if ezpreference( 'admin_navigation_class_temlates' )}
    {include uri='design:class/templates.tpl'}
{/if}
