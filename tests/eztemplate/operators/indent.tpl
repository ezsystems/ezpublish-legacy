{let input1="test" input2="test
test2
test3" indent=4 type1='tab' type2='space' type3='custom' type3_text='foo '}
**** input=variable, count=variable, type=variable ****
<{$input1|indent( $indent, $type1 )}>
<{$input1|indent( $indent, $type2 )}>
<{$input1|indent( $indent, $type3, $type3_text )}>
<{$input2|indent( $indent, $type1 )}>
<{$input2|indent( $indent, $type2 )}>
<{$input2|indent( $indent, $type3, $type3_text )}>
**** input=static, count=variable, type=variable ****
<{"test"|indent( $indent, $type1 )}>
<{"test"|indent( $indent, $type2 )}>
<{"test"|indent( $indent, $type3, $type3_text )}>
<{"test
test2
test3"|indent( $indent, $type1 )}>
<{"test
test2
test3"|indent( $indent, $type2 )}>
<{"test
test2
test3"|indent( $indent, $type3, $type3_text )}>
**** input=static, count=variable, type=static ****
<{"test"|indent( $indent, 'tab' )}>
<{"test"|indent( $indent, 'space' )}>
<{"test"|indent( $indent, 'custom', 'foo ' )}>
<{"test
test2
test3"|indent( $indent, 'tab' )}>
<{"test
test2
test3"|indent( $indent, 'space' )}>
<{"test
test2
test3"|indent( $indent, 'custom', 'foo ' )}>
{/let}

**** input=static, count=static, type=static ****
<{"test"|indent( 4, 'tab' )}>
<{"test"|indent( 4, 'space' )}>
<{"test"|indent( 4, 'custom', 'foo ' )}>
<{"test
test2
test3"|indent( 4, 'tab' )}>
<{"test
test2
test3"|indent( 4, 'space' )}>
<{"test
test2
test3"|indent( 4, 'custom', 'foo ' )}>
