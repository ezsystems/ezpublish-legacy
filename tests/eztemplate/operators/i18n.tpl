{let Nodes=hash( item, hash( name, 'param value', contentobject_id, 42 ) )}
`{'Some simple text'|i18n( 'nocontext' )}`
`{'Some simple text with indexed parameter: %1'|i18n( 'nocontext',, array( 'param value' ) )}`
`{'Some simple text with named parameter: %param'|i18n( 'nocontext',, hash( '%param', 'param value' ) )}`
`{'Some simple text with dynamic named parameter: %quoted_param'|i18n( 'nocontext',, hash( '%quoted_param', concat( '"', $Nodes.item.name, '"' ) ) )}`
`<a href={concat( 'content/edit/', $Nodes.item.contentobject_id )}><img title="{'Click here to edit the contents of %quoted_name'|i18n( 'design/admin/layout',,hash( '%quoted_name', concat( '"', $Nodes.item.name, '"' ) ) )|wash}" /></a>`
{/let}
