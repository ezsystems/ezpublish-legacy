<?php
    function glob_this( $path, $subdir, $suffix, $orig_path = '' )
    {
        if ($orig_path == '') {
            $orig_path = $path;
        }
        $entries = array();
        if ( $subdir != '' )
        {
            $path .= '/'. $subdir; 
        }
        $dir = glob( $path. '/*' );
        if ( $dir )
        {
            $suffixLength = 0 - strlen( $suffix );
            foreach ( $dir as $entry )
            {
                if ( is_dir( $entry ) )
                {
                    $sub_entries = glob_this( $entry, '', $suffix, $orig_path );
                    $entries = array_merge( $entries, $sub_entries );
                }
                else
                {
                    if ( substr( $entry, $suffixLength ) == $suffix ) {
                        $entries[] = $entry;
                    }
                }
            }
            $quoted = str_replace("/", '\/', $orig_path);
            $entries = preg_replace("/$quoted(\/)?/", '', $entries);
        }
        return $entries;
    }

    include "lib/ezfile/classes/ezdir.php";

    $b0 = xdebug_get_function_count();
    $a1 = eZDir::recursiveFindRelative( 'design/standard/templates', '', 'tpl' );
    $b1 = xdebug_get_function_count();
    $a2 = glob_this( 'design/standard/templates', '', 'tpl' );
    $b2 = xdebug_get_function_count();
    var_dump($a1, $a2);
    var_dump(array_diff($a1, $a2));
    echo $b1 - $b0, '-', $b2 - $b1, "\n";
?>
