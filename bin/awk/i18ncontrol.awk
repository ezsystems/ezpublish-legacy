#!/usr/bin/awk -f
BEGIN {
  FileContext="";
}

{
# fetch the translationstring and the context. This regexp is very messy right now. Feel free to fix it :)
  #TODO: need to fetch the rest of the line to see if there are more than one i18n per line
  gotMatch = match( $0, /[\'\"]((\\\'|[^\'])*)[\'\"][ \t\f\n\r\v]*\|[ \t\f\n\r\v]*i18n\([ \t\f\n\r\v]*[\'\"]([^\'\"]*)[\'\"]/, matches );
  while( gotMatch ) # Line contains i18n
  {
    transString = matches[1];
    context = matches[3];
#    print transString;
#    print context;
    # context related checks
    if( FileContext == "" )
    {
      FileContext = context;
      printf( "######## %s ########\n", FILENAME );
      printf( "%i: Context is set: %s\n", NR, context );
      # sanity check context
      if( FileContext ~ /^\// || FileContext ~ /\/$/ )
      {
        printf( "%i: Bad context [%s]\n", NR, FileContext );
        exit;
      }
      else if( FileContext !~ /^design/ )
        printf( "%i: Context should start with design\n", NR );
      else if( FILENAME ~ /^design/   )
      {
        # fetch design name
        match( FILENAME , /[^\/]*\/([^\/]*)/, matches);
        probableContext = matches[1];
        match( FileContext , /[^\/]*\/([^\/]*)/, matches);
        if( matches[1] != probableContext )
          printf( "%i: Probable mismatch between design and translation context [%s]\n", NR, FileContext );
      }
    }
    else if( context != FileContext ) # check that new context is similar to old one
    {
        printf( "%i: Detected context inconsistency [%s]\n", NR, context );
    }

    # translation string related checks
    if( transString ~ /:$/ )
    {
      printf( "%i: Translation string ends with ':' [%s]\n", NR, transString );
    }

    $0 = substr( $0, RSTART + RLENGTH );
    gotMatch = match( $0, /[\'\"]((\\\'|[^\'])*)[\'\"][ \t\f\n\r\v]*\|[ \t\f\n\r\v]*i18n\([ \t\f\n\r\v]*[\'\"]([^\'\"]*)[\'\"]/, matches );
  }
}

END {
  if( FileContext != "" )
    printf( "----------------------------------------------------------\n\n\n");
}
