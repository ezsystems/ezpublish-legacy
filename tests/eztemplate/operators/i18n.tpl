{let Node=hash( item, hash( name, 'param value' ) )}
`{'Some simple text'|i18n( 'nocontext' )}`
`{'Some simple text with indexed parameter: %1'|i18n( 'nocontext',, array( 'param value' ) )}`
`{'Some simple text with named parameter: %param'|i18n( 'nocontext',, hash( '%param', 'param value' ) )}`
`{'Some simple text with dynamic named parameter: %quoted_param'|i18n( 'nocontext',, hash( '%quoted_param', concat( '"', $Node.item.name, '"' ) ) )}`
{/let}
