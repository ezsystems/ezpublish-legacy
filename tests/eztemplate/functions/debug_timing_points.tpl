{let id="abc"}

{debug-timing-point id="abc"}

{let first=1 second="2" third=array( 1, 5, "test" )}

{sum( $first, $second)} != {mul( sum( $first, $second ), $third[1] )}

{/let}

{/debug-timing-point}

{debug-timing-point id=$id}

{let first=1 second="2" third=array( 1, 5, "test" )}

{sum( $first, $second)} != {mul( sum( $first, $second ), $third[1] )}

{/let}

{/debug-timing-point}

{/let}
