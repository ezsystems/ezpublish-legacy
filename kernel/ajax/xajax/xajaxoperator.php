<?php

class XajaxOperator
{
    var $Operators;

    function XajaxOperator( )
    {
        $this->Operators = array( 'xajax_javascript', 'xajax_app_javascript' );
    }

    function &operatorList( )
    {
        return $this->Operators;
    }

    /*!
     \return true to tell the template engine that the parameter list exists per operator type.
    */
    function namedParameterPerOperator()
    {
        return true;
    }

        /*!
     See eZTemplateOperator::namedParameterList()
    */
    function namedParameterList()
    {
        return array
        (
            'xajax_javascript' => array(),
            'xajax_app_javascript' => array( 'filename' => array( "filename"      => "string",
                                                                   "required"  => true,
                                                                   "default"   => false ) ),
        );
    }

    /*!
     \reimp
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        switch ( $operatorName )
        {
            case 'xajax_javascript':
                {
                    include_once( 'lib/3dparty/xajax/xajax.inc.php' );
                    include_once( 'lib/ezutils/classes/ezuri.php' );
                    $xajaxModuleView = '/ajax/call';
                    eZURI::transformURI( $xajaxModuleView );
                    $xajax = new xajax( $xajaxModuleView );
                    
                    include_once( 'lib/ezutils/classes/ezextension.php' );
                    include_once( 'lib/ezutils/classes/ezini.php' );
                
                    $ini =& eZINI::instance( 'xajax.ini' );
                    
                    if ( $ini->variable( 'AjaxSettings', 'DebugAlert' ) == 'enabled' )
                    {
                        $xajax->debugOn();
                    }

                    $functionFiles = $ini->variable( 'AjaxSettings', 'AvailableFunctions' );
                    $ajaxAppDirs = $ini->variable( 'AjaxSettings', 'AjaxAppDirectories' );

                    $extensionDirectories = array_merge( 'xajax_app', $ini->variable( 'ExtensionSettings', 'ExtensionDirectories' ) );
                    $directoryList = eZExtension::expandedPathList( $extensionDirectories, 'xajax_app' );
                    $directoryList = array_merge( $ajaxAppDirs, $directoryList );
                
                    if ( count( $functionFiles ) > 0 )
                    {
                        foreach ( $functionFiles as $function => $functionFile )
                        {
                            foreach ( $directoryList as $directory )
                            {
                                $handlerFile = $directory . '/' . strtolower( $functionFile ) . '.php';
                                if ( file_exists( $handlerFile ) )
                                {
                                    $xajax->registerExternalFunction( $function, $handlerFile );
                                }
                            }
                        }
                    }
                    
                    include_once( 'lib/ezutils/classes/ezsys.php' );
                    $sys =& eZSys::instance();
                    $operatorValue = $xajax->getJavascript( $sys->wwwDir() . '/design/standard/javascript/3dparty/xajax/', 'xajax.js', 'design/standard/javascript/3dparty/xajax/xajax.js' );
                    if ( $ini->variable( 'AjaxSettings', 'ProgressIndicator' ) == 'enabled' ) 
                    {
                        //js stuff that add progress indicator
                       $operatorValue.='<script>
                        //ProgressIndicator stuff
                        var b=document.getElementsByTagName("body")[0];
                        var pImg=new Image();
                        pImg.src = "/design/standard/images/ajax-activity_indicator.gif";
                        b.appendChild( pImg );
                        pImg.setAttribute("id", "spinner");
                        pImg.style.display="none";
                        pImg.style.position="absolute";
                        pImg.style.top="50%";
                        pImg.style.left="50%";
                        pImg.style.backgroundColor="#CCC";
                        
                        // keep around the old call function
                        xajax.realCall = xajax.call;
                        //override the call function to bend to our wicked ways
                        xajax.call = function(sFunction, aArgs, sRequestType)
                        {
                            //show the spinner
                            screenProp = ezjslib_getScreenProperties();
                            screenCenterY = screenProp.ScrollY + screenProp.Height/2;
                            screenCenterX = screenProp.ScrollX + screenProp.Width/2;
                            pImg = this.$("spinner");
                            pImg.style.top = (screenCenterY - pImg.height/2 ) + "px";
                            pImg.style.left = ( screenCenterX - pImg.width/2 ) + "px";
                            pImg.style.display = "inline";
                            //call the old call function
                            return this.realCall(sFunction, aArgs, sRequestType);
                        }
                        
                        //save the old processResponse function for later
                        xajax.realProcessResponse = xajax.processResponse;
                        //override the processResponse function
                        xajax.processResponse = function(xml)
                        {
                            //hide the spinner
                            this.$("spinner").style.display = "none";
                            //call the real processResponse function
                            return this.realProcessResponse(xml);
                        }

                           </script>';
                    }

                }break;
        case 'xajax_app_javascript':
                {
                    $jsFile = $namedParameters['filename'];

/*
                    //todo check if filename and is exists
                    include_once( 'lib/ezutils/classes/ezsys.php' );
                    $sys =& eZSys::instance();

                    if ( !file_exists( $sys->wwwDir().$jsFile ) ) 
                    {
                        eZDebug::writeError( 'Wrong operator parameter: ' .$sys->wwwDir().$jsFile.' can\'t be found' , 'xajaxoperator.php' );
                    }
*/                    

                    $operatorValue = '<script type="text/javascript" src="'.$jsFile.'"></script>';
                }break;


            default:
                {
                    eZDebug::writeError( 'Unknown operator: ' . $operatorName, 'xajaxoperator.php' );
                }
        }
    }

    /// \privatesection
    var $Operators;
};

?>
