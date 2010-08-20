<?php

/*
	This file should not be modified. This is so that future versions can be
	dropped into place as servers are updated.

	Version 2.3.0: Supports phantoms.
	Version 2.2.1: Supports channel comments.
*/


function StrKey( $src, $key, &$res )
{
	$key .= " ";
	if ( strncasecmp( $src, $key, strlen( $key ) ) )
		return false;

	$res = substr( $src, strlen( $key ) );
	return true;
}




function StrSplit( $src, $sep, &$d1, &$d2 )
{
	$pos = strpos( $src, $sep );
	if ( $pos === false )
	{
		$d1 = $src;
		return;
	}

	$d1 = substr( $src, 0, $pos );
	$d2 = substr( $src, $pos + 1 );
}





function StrDecode( &$src )
{
	$res = "";

	for ( $i = 0; $i < strlen( $src ); )
	{
		if ( $src[ $i ] == '%' )
		{
			$res .= sprintf( "%c", intval( substr( $src, $i + 1, 2 ), 16 ) );
			$i += 3;
			continue;
		}

		$res .= $src[ $i ];
		$i += 1;
	}

	return( $res );
}




class CVentriloClient
{
	var	$m_uid;			// User ID.
	var	$m_admin;		// Admin flag.
	var $m_phan;		// Phantom flag.
	var $m_cid;			// Channel ID.
	var $m_ping;		// Ping.
	var	$m_sec;			// Connect time in seconds.
	var	$m_name;		// Login name.
	var	$m_comm;		// Comment.

	function Parse( $str )
	{
		$ary = explode( ",", $str );

		for ( $i = 0; $i < count( $ary ); $i++ )
		{
			StrSplit( $ary[ $i ], "=", $field, $val );

			if ( strcasecmp( $field, "UID" ) == 0 )
			{
				$chatBot->m_uid = $val;
				continue;
			}

			if ( strcasecmp( $field, "ADMIN" ) == 0 )
			{
				$chatBot->m_admin = $val;
				continue;
			}

			if ( strcasecmp( $field, "CID" ) == 0 )
			{
				$chatBot->m_cid = $val;
				continue;
			}

			if ( strcasecmp( $field, "PHAN" ) == 0 )
			{
				$chatBot->m_phan = $val;
				continue;
			}

			if ( strcasecmp( $field, "PING" ) == 0 )
			{
				$chatBot->m_ping = $val;
				continue;
			}

			if ( strcasecmp( $field, "SEC" ) == 0 )
			{
				$chatBot->m_sec = $val;
				continue;
			}

			if ( strcasecmp( $field, "NAME" ) == 0 )
			{
				$chatBot->m_name = StrDecode( $val );
				continue;
			}

			if ( strcasecmp( $field, "COMM" ) == 0 )
			{
				$chatBot->m_comm = StrDecode( $val );
				continue;
			}
		}
	}
}

class CVentriloChannel
{
	var	$m_cid;		// Channel ID.
	var	$m_pid;		// Parent channel ID.
	var	$m_prot;	// Password protected flag.
	var	$m_name;	// Channel name.
	var	$m_comm;	// Channel comment.

	function Parse( $str )
	{
		$ary = explode( ",", $str );

		for ( $i = 0; $i < count( $ary ); $i++ )
		{
			StrSplit( $ary[ $i ], "=", $field, $val );

			if ( strcasecmp( $field, "CID" ) == 0 )
			{
				$chatBot->m_cid = $val;
				continue;
			}

			if ( strcasecmp( $field, "PID" ) == 0 )
			{
				$chatBot->m_pid = $val;
				continue;
			}

			if ( strcasecmp( $field, "PROT" ) == 0 )
			{
				$chatBot->m_prot = $val;
				continue;
			}

			if ( strcasecmp( $field, "NAME" ) == 0 )
			{
				$chatBot->m_name = StrDecode( $val );
				continue;
			}

			if ( strcasecmp( $field, "COMM" ) == 0 )
			{
				$chatBot->m_comm = StrDecode( $val );
				continue;
			}
		}
	}
}


class CVentriloStatus
{
	// These need to be filled in before issueing the request.

	var	$m_cmdprog;		// Path and filename of external process to execute. ex: /var/www/html/ventrilo_status
	var	$m_cmdcode;		// Specific status request code. 1=General, 2=Detail.
	var	$m_cmdhost;		// Hostname or IP address to perform status of.
	var	$m_cmdport;		// Port number of server to status.

	// These are the result variables that are filled in when the request is performed.

	var	$m_error;		// If the ERROR: keyword is found then this is the reason following it.

	var	$m_name;				// Server name.
	var	$m_phonetic;			// Phonetic spelling of server name.
	var	$m_comment;				// Server comment.
	var	$m_maxclients;			// Maximum number of clients.
	var	$m_voicecodec_code;		// Voice codec code.
	var $m_voicecodec_desc;		// Voice codec description.
	var	$m_voiceformat_code;	// Voice format code.
	var	$m_voiceformat_desc;	// Voice format description.
	var	$m_uptime;				// Server uptime in seconds.
	var	$m_platform;			// Platform description.
	var	$m_version;				// Version string.

	var	$m_channelcount;		// Number of channels as specified by the server.
	var	$m_channelfields;		// Channel field names.
	var	$m_channellist;			// Array of CVentriloChannel's.

	var	$m_clientcount;			// Number of clients as specified by the server.
	var	$m_clientfields;		// Client field names.
	var $m_clientlist;			// Array of CVentriloClient's.

	function Parse( $str )
	{
		// Remove trailing new line.

		$pos = strpos( $str, "\n" );
		if ( $pos === false )
		{
		}
		else
		{
			$str = substr( $str, 0, $pos );
		}

		// Begin parsing for keywords.

		if ( StrKey( $str, "ERROR:", $val ) )
		{
			$chatBot->m_error = $val;
			return -1;
		}

		if ( StrKey( $str, "NAME:", $val ) )
		{
			$chatBot->m_name = StrDecode( $val );
			return 0;
		}

		if ( StrKey( $str, "PHONETIC:", $val ) )
		{
			$chatBot->m_phonetic = StrDecode( $val );
			return 0;
		}

		if ( StrKey( $str, "COMMENT:", $val ) )
		{
			$chatBot->m_comment = StrDecode( $val );
			return 0;
		}

		if ( StrKey( $str, "AUTH:", $chatBot->m_auth ) )
			return 0;

		if ( StrKey( $str, "MAXCLIENTS:", $chatBot->m_maxclients ) )
			return 0;

		if ( StrKey( $str, "VOICECODEC:", $val ) )
		{
			StrSplit( $val, ",", $chatBot->m_voicecodec_code, $desc );
			$chatBot->m_voicecodec_desc = StrDecode( $desc );
			return 0;
		}

		if ( StrKey( $str, "VOICEFORMAT:", $val ) )
		{
			StrSplit( $val, ",", $chatBot->m_voiceformat_code, $desc );
			$chatBot->m_voiceformat_desc = StrDecode( $desc );
			return 0;
		}

		if ( StrKey( $str, "UPTIME:", $val ) )
		{
			$chatBot->m_uptime = $val;
			return 0;
		}

		if ( StrKey( $str, "PLATFORM:", $val ) )
		{
			$chatBot->m_platform = StrDecode( $val );
			return 0;
		}

		if ( StrKey( $str, "VERSION:", $val ) )
		{
			$chatBot->m_version = StrDecode( $val );
			return 0;
		}

		if ( StrKey( $str, "CHANNELCOUNT:", $chatBot->m_channelcount ) )
			return 0;

		if ( StrKey( $str, "CHANNELFIELDS:", $chatBot->m_channelfields ) )
			return 0;

		if ( StrKey( $str, "CHANNEL:", $val ) )
		{
			$chan = new CVentriloChannel;
			$chan->Parse( $val );

			$chatBot->m_channellist[ count( $chatBot->m_channellist ) ] = $chan;
			return 0;
		}

		if ( StrKey( $str, "CLIENTCOUNT:", $chatBot->m_clientcount ) )
			return 0;

		if ( StrKey( $str, "CLIENTFIELDS:", $chatBot->m_clientfields ) )
			return 0;

		if ( StrKey( $str, "CLIENT:", $val ) )
		{
			$client = new CVentriloClient;
			$client->Parse( $val );

			$chatBot->m_clientlist[ count( $chatBot->m_clientlist ) ] = $client;
			return 0;
		}

		// Unknown key word. Could be a new keyword from a newer server.

		return 1;
	}

	function ChannelFind( $cid )
	{
		for ( $i = 0; $i < count( $chatBot->m_channellist ); $i++ )
			if ( $chatBot->m_channellist[ $i ]->m_cid == $cid )
				return( $chatBot->m_channellist[ $i ] );

		return NULL;
	}

	function ChannelPathName( $idx )
	{
		$chan = $chatBot->m_channellist[ $idx ];
		$pathname = $chan->m_name;

		for(;;)
		{
			$chan = $chatBot->ChannelFind( $chan->m_pid );
			if ( $chan == NULL )
				break;

			$pathname = $chan->m_name . "/" . $pathname;
		}

		return( $pathname );
	}

	function Request()
	{
		$cmdline = $chatBot->m_cmdprog;
		$cmdline .= " -c" . $chatBot->m_cmdcode;
		$cmdline .= " -t" . $chatBot->m_cmdhost;

		if ( strlen( $chatBot->m_cmdport ) )
		{
			$cmdline .= ":" . $chatBot->m_cmdport;

			// For password to work you MUST provide a port number.

			if ( strlen( $chatBot->m_cmdpass ) )
				$cmdline .= ":" . $chatBot->m_cmdpass;
		}

		// Execute the external command.
		$pipe = popen( $cmdline, "r" );
		if ( $pipe === false )
		{
			$chatBot->m_error = "PHP Unable to spawn shell.";
			return -10;
		}

		// Process the results coming back from the shell.

		$cnt = 0;

		while( !feof( $pipe ) )
		{
			$s = fgets( $pipe, 1024 );

			if ( strlen( $s ) == 0 )
				continue;

			$rc = $chatBot->Parse( $s );
			if ( $rc < 0 )
			{
				pclose( $pipe );
				return( $rc );
			}

			$cnt += 1;
		}

		pclose( $pipe );

		if ( $cnt == 0 )
		{
			// This is possible since the shell might not be able to find
			// the specified process but the shell did spawn. More likely to
			// occur then the -10 above.

			$chatBot->m_error = "PHP Unable to start external status process.";
			return -11;
		}

		return 0;
	}
};

?>