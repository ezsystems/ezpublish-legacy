<?php
/***************************************
** Filename.......: class.smtp.inc
** Project........: SMTP Class
** Version........: 1.0.5
** Last Modified..: 21 December 2001
***************************************/

    class smtp
    {
        const STATUS_NOT_CONNECTED = 1;
        const STATUS_CONNECTED = 2;
        const CRLF = "\r\n";

        public $authenticated;
        public $connection;
        public $recipients;
        public $CcRecipients;
        public $BccRecipients;
        public $headers;
        public $timeout;
        public $errors;
        public $status;
        public $body;
        public $from;
        public $host;
        public $port;
        public $helo;
        public $auth;
        public $user;
        public $pass;

        /***************************************
        ** Constructor function. Arguments:
        ** $params - An assoc array of parameters:
        **
        **   host    - The hostname of the smtp server        Default: localhost
        **   port    - The port the smtp server runs on        Default: 25
        **   helo    - What to send as the HELO command        Default: localhost
        **             (typically the hostname of the
        **             machine this script runs on)
        **   auth    - Whether to use basic authentication    Default: FALSE
        **   user    - Username for authentication            Default: <blank>
        **   pass    - Password for authentication            Default: <blank>
        **   timeout - The timeout in seconds for the call    Default: 5
        **             to fsockopen()
        ***************************************/

        function smtp( $params = array() )
        {
            $this->authenticated = FALSE;
            $this->timeout       = 5;
            $this->status        = smtp::STATUS_NOT_CONNECTED;
            $this->host          = 'localhost';
            $this->port          = 25;
            $this->helo          = 'localhost';
            $this->auth          = FALSE;
            $this->user          = '';
            $this->pass          = '';
            $this->errors        = array();

            foreach ( $params as $key => $value )
            {
                $this->$key = $value;
            }
        }

        /***************************************
        ** Connect function.
        ** It will connect to the server and send
        ** the HELO command.
        ***************************************/

        function connect($params = array())
        {
            $this->connection = fsockopen( $this->host, $this->port, $errno, $errstr, $this->timeout );
            if ( function_exists( 'socket_set_timeout' ) )
            {
                @socket_set_timeout( $this->connection, 5, 0 );
            }

            $greeting = $this->get_data();
            if ( is_resource( $this->connection ) )
            {
                return $this->auth ? $this->ehlo() : $this->helo();
            }
            else
            {
                $this->errors[] = 'Failed to connect to server: ' . $errstr;
                return FALSE;
            }
        }

        /***************************************
        ** Function which handles sending the mail.
        ** Arguments:
        ** $params    - Optional assoc array of parameters.
        **            Can contain:
        **              recipients - Indexed array of recipients
        **              from       - The from address. (used in MAIL FROM:),
        **                           this will be the return path
        **              headers    - Indexed array of headers, one header per array entry
        **              body       - The body of the email
        **            It can also contain any of the parameters from the connect()
        **            function
        ***************************************/

        function send( $params = array() )
        {
            foreach ( $params as $key => $value )
            {
                $this->set( $key, $value );
            }

            if ( $this->is_connected() )
            {
                // Do we auth or not? Note the distinction between the auth variable and auth() function
                if ( $this->auth AND !$this->authenticated )
                {
                    if ( !$this->auth() )
                        return FALSE;
                }
                $this->mail( $this->from );
                if ( is_array( $this->recipients ) )
                    foreach ( $this->recipients as $value )
                        $this->rcpt( $value );
                else
                    $this->rcpt( $this->recipients );

                if ( is_array( $this->CcRecipients ) )
                    foreach( $this->CcRecipients as $value )
                        $this->rcpt( $value );
                else
                    $this->rcpt( $this->CcRecipients );

                if ( is_array( $this->BccRecipients ) )
                    foreach ( $this->BccRecipients as $value )
                        $this->rcpt( $value );
                else
                    $this->rcpt( $this->BccRecipients );

                if ( !$this->data() )
                    return FALSE;

                // Transparency
                $headers = str_replace( smtp::CRLF.'.', smtp::CRLF.'..', trim( implode( smtp::CRLF, $this->headers ) ) );
                $body    = str_replace( smtp::CRLF.'.', smtp::CRLF.'..', $this->body );
                $body    = $body[0] == '.' ? '.'.$body : $body;

                $this->send_data( $headers );
                $this->send_data( '' );
                $this->send_data( $body );
                $this->send_data( '.' );

                $result = ( substr( trim( $this->get_data() ), 0, 3) === '250' );
                return $result;
            }
            else
            {
                $this->errors[] = 'Not connected!';
                return FALSE;
            }
        }

        /***************************************
        ** Function to implement HELO cmd
        ***************************************/

        function helo()
        {
            return( $this->send_cmd( 'HELO ' . $this->helo, '250' ) );
        }


        /***************************************
        ** Function to implement EHLO cmd
        ***************************************/

        function ehlo()
        {
            /* return the result of the EHLO command */
            return ( $this->send_cmd( 'EHLO ' . $this->helo, '250' ) );
        }

        /***************************************
        ** Function to implement RSET cmd
        ***************************************/

        function rset()
        {
            /* return the result of the RSET command */
            return ( $this->send_cmd( 'RSET', '250' ) );
        }

        /***************************************
        ** Function to implement QUIT cmd
        ***************************************/

        function quit()
        {
            /* if QUIT OK */
            if ( $this->send_cmd( 'QUIT', '221' ) )
            {
                /* unset the connection flag and return TRUE */
                $this->status = smtp::STATUS_NOT_CONNECTED;
                return TRUE;
            }
            /* in other case return FALSE */
            return FALSE;
        }

        /***************************************
        ** Function to implement AUTH cmd
        ***************************************/

        function auth()
        {
            /* if the connection is made */
            if ( $this->send_cmd('AUTH LOGIN', '334' ) )
            {
                /* if sending username ok */
                if ( $this->send_cmd( base64_encode( $this->user ), '334' ) )
                {
                    /* if sending password ok */
                    if ( $this->send_cmd( base64_encode( $this->pass ), '235' ) )
                    {
                        /* set the authenticated  flag and return TRUE */
                        $this->authenticated = TRUE;
                         return TRUE;
                    }
                }
            }
            /* in other case return FALSE */
            return FALSE;
        }

        /***************************************
        ** Function that handles the MAIL FROM: cmd
        ***************************************/

        function mail( $from )
        {
            /* normalize the from field */
            if ( !preg_match( "/<.+>/", $from ) )
                $from = '<' . $from .'>';

            /* return the result of the MAIL FROM command */
            return ( $this->send_cmd('MAIL FROM:' . $from . '', '250' ) );
        }

        /***************************************
        ** Function that handles the RCPT TO: cmd
        ***************************************/

        function rcpt( $to )
        {
            /* normalize the to field */
            if ( !preg_match( "/<.+>/", $to ) )
                $to = '<' . $to .'>';

            /* return the result of the RCPT TO command */
            return ( $this->send_cmd( 'RCPT TO:' . $to . '', '250' ) );
        }


        /***************************************
        ** Function that sends the DATA cmd
        ***************************************/

        function data()
        {
            /* return the result of the RCPT TO command */
            return ( $this->send_cmd('DATA', '354' ) );
        }

        /***************************************
        ** Function to determine if this object
        ** is connected to the server or not.
        ***************************************/

        function is_connected()
        {
            return ( is_resource( $this->connection ) AND ( $this->status === smtp::STATUS_CONNECTED ) );
        }

        /***************************************
        ** Function to send a bit of data
        ***************************************/

        function send_data( $data )
        {
            if ( is_resource( $this->connection ) )
            {
                return fwrite( $this->connection, $data.smtp::CRLF, strlen( $data ) + 2 );
            }
            else
                return FALSE;
        }

        /***************************************
        ** Function to get data.
        ***************************************/

        function get_data()
        {
            $return = '';
            $line   = '';
            $loops  = 0;

            if ( is_resource( $this->connection ) )
            {
                while ( ( strpos( $return, smtp::CRLF ) === FALSE OR substr( $line, 3, 1 ) !== ' ' ) AND $loops < 100 )
                {
                    $line    = fgets( $this->connection, 512 );
                    $return .= $line;
                    $loops++;
                }
                return $return;
            }
            else
                return FALSE;
        }

        /***************************************
        ** Sets a variable
        ***************************************/

        function set( $var, $value )
        {
            $this->$var = $value;
            return TRUE;
        }

        /********************************************************
        ** Function to simply send a command to the smtp socket
        *********************************************************/
        function send_cmd( $msg, $answer )
        {
            /* if the connection is made */
            if ( $error = is_resource( $this->connection ) )
            {
                /* if sending DATA ok */
                if ( $error = $this->send_data( $msg ) )
                {
                    /* Wait for server answer */
                    $error = $this->get_data();

                    /* return TRUE if the server answered the expected tag */
                    if( substr( trim( $error ), 0, 3 ) === $answer )
                    {
                        return TRUE;
                    }
                }
            }
            /* else return FALSE and set an error */
            $this->errors[] = $msg . ' command failed, output: ' . $error;
            return FALSE;
        }

    } // End of class
?>
