{section loop=array('Button1','Button2','Button3','Button4','Button5')}

{$:item}:
{image( $:item
, imagefile( 'tests/eztemplate/design/standard/images/blank.gif' )
, array( $:item |texttoimage( array(' ', false(),'10',false(),'#000000','#000000', false() ) ), hash('x', '0' ,'y', '0' , 'halign', 'left', 'valign', 'top' )  )
)}
{/section}