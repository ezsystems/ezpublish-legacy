{let arr=array( 1, 2, 3, 4, 5, 6 ) hash1=hash( 1, 'a', 2, 'b', 3, 'c', 6, 'd' ) hash2=hash( 1, 'A', 3, 'B', 6, 'C' )}
first_set( $arr[2], false() )='{first_set( $arr[2], false() )}'
first_set( $arr[10], false() )='{first_set( $arr[10], false() )}'
first_set( $hash2[2], $hash1[2], false() )='{first_set( $hash2[2], $hash1[2], false() )}'
first_set( $hash2[3], $hash1[3], false() )='{first_set( $hash2[3], $hash1[3], false() )}'
first_set( $arr[6], $hash1[6], $hash2[6], false() )='{first_set( $arr[6], $hash1[6], $hash2[6], false() )}'
{/let}
