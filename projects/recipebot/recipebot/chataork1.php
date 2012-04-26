<?php

  /*
   * $Id: AOChat.php,v 1.8 2002/06/14 09:27:39 pickett Exp $
   *
   * AOChat, a PHP class for talking with the Anarchy Online chat servers. 
   * It requires the sockets extension (to connect to the chat server..)
   * from PHP 4.1.0+ and either the GMP or BCMath extension (for generating
   * and calculating the login keys) to work.
   *
   * I used the official java chat client[1] and Slicer's AO::Chat perl
   * module[2] as a reference for this class.
   *
   * <auno@auno.org>
   * <http://auno.org/aochat/>
   *
   * [1]: <http://www.anarchy-online.com/content/community/forumsandchat/>
   * [2]: <http://www.hackersquest.org/ao/>
   *
   *
   * Quick and dirty example...
   *
   *   require_once "AOChat.php";
   *
   *   $aoc = new AOChat("callback");
   *   $chars = $aoc -> authenticate("username", "password");
   *   $aoc -> login($chars[0]["name"]);
   *   while(true)
   *   {
   *     $aoc -> wait_for_packet();
   *   }
   *
   *   function callback($type, $args)
   *   {
   *     ...
   *   }
   *
   */
   
  
  if((float)phpversion() < 4.1)
  {
    die("AOChat class needs PHP version >= 4.1.0 to work.\n");
  }
  
  if(!extension_loaded("sockets"))
  {
    die("AOChat class needs Sockets extension to work.\n");
  }

  if(!extension_loaded("gmp") && !extension_loaded("bcmath"))
  {
    die("AOChat class needs either GMP or BCMath extension to work.\n");
  }

  set_time_limit(0);
  
  class AOChat
  {
    var $id, $gid, $socket, $callback, $debug, $chars, $state, $cbargs;

/****************************/
	function addBuddy($char) 
	{
		$char = ucfirst(strtolower($char));
		if($uid = $this->lookup_user($char)) 
		{
			$this->send_packet(new AOChatPacket("out", 40, array($uid,1)));
			return TRUE;
		}
		return FALSE;
	}

	function remBuddy($char) {
		$char = ucfirst(strtolower($char));
		if($uid = $this->lookup_user($char)) 
		{
			$this->send_packet(new AOChatPacket("out", 41, $uid));
			return TRUE;
		}
		return FALSE;
	}

    function send_DE($msg, $blob = "\0")
    {
      $this -> send_packet(new AOChatPacket("out", 57, array(32772, $msg, $blob)));
      
      return true;
    }





/****************************/




    function AOChat($cb, $args = 0, &$etat)
    {
      $etat=1;
      $this -> callback = $cb;
      $this -> cbargs   = $args;
      $this -> id  = array();
      $this -> gid = array();
      $this -> state = "auth";
      
      $s = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
      if(!is_resource($s))
        die("Could not create socket.\n");
        
      if(socket_connect($s, "chat2.d1.funcom.com", 7012) == false)
      {
        echo "Could not connect to the chat server.\n";
        $etat=0;
        return;
      }
      
      $this -> socket = $s;
      
      $packet = $this -> get_packet();
      if(!is_object($packet) || $packet -> type != 0)
        die("Invalid greeting packet.\n");
    }
    
    function wait_for_packet($time = 1)
    {
      $sec = (int)$time;
      if(is_float($time))
        $usec = (int)($time * 1000000 % 1000000);
      else
        $usec = 0;
        
      if(!socket_select($a = array($this->socket), $b = null, $c = null, $sec, $usec))
        return false;
      
      return $this -> get_packet();
    }
    
    function get_packet()
    {
      $head = socket_read($this -> socket, 4);
      if(strlen($head) != 4)
        return false;
      
      list(, $type, $len) = unpack("n2", $head);
      
      $data = socket_read($this -> socket, $len);
      if(strlen($data) != $len)
        return false;
      
      if(is_resource($this -> debug))
      {
        fwrite($this -> debug, "<<<<<\n");
        fwrite($this -> debug, $head);
        fwrite($this -> debug, $data);
        fwrite($this -> debug, "\n=====\n");
      }
      
      $packet = new AOChatPacket("in", $type, $data);
      
      switch($type)
      {
        case 0 :
          $this -> serverseed = $packet -> args[0];
          break;
        
        case 20 :
        case 21 :
          list($id, $name) = $packet -> args;
          $id   = "" . $id;
          $name = ucfirst(strtolower($name));
          $this -> id[$id]   = $name;
          $this -> id[$name] = $id;
          break;
        
        case 60 :
          list($gid, $name) = $packet -> args;
          $this -> gid[$gid]  = $name;
          $this -> gid[$name] = $gid;
          break;
      }
      
      if(function_exists($this -> callback))
      {
        call_user_func($this -> callback, $packet -> type, $packet -> args, $this -> cbargs);
      }
      
      return $packet;
    }
    
    function send_packet($packet)
    {
      $data = pack("n2", $packet->type, strlen($packet->data)) . $packet -> data;
      if(is_resource($this -> debug))
      {
        fwrite($this -> debug, ">>>>>\n");
        fwrite($this -> debug, $data);
        fwrite($this -> debug, "\n=====\n");
      }
      socket_write($this -> socket, $data, strlen($data));
    }
    
    function authenticate($username, $password)
    {
      if($this -> state != "auth")
        die("AOChat: not expecting authentication.\n");
        
      $key = $this -> generate_login_key($this -> serverseed, $username, $password);
      $pak = new AOChatPacket("out", 2, array(0, $username, $key));
      $this -> send_packet($pak);
      $packet = $this -> get_packet();
      if($packet -> type != 7)
      {
        return false;
      }

      $chars = array();
      for($i=0;$i<sizeof($packet -> args[0]);$i++)
      {
        $chars[] = array(
          "id"     => $packet -> args[0][$i],
          "name"   => strtolower($packet -> args[1][$i]),
          "level"  => $packet -> args[2][$i],
          "online" => $packet -> args[3][$i]);
      }
      
      $this -> username = $username;
      $this -> chars    = $chars;
      $this -> state    = "login";
      
      return $chars;
    }
    
    function login($char)
    {
      if($this -> state != "login")
        die("AOChat: not expecting login.\n");
        
      if(is_int($char))
        $field = "id";
      else if(is_string($char))
        $field = "name";
      
      if(!is_array($char))
      {
        if(empty($field))
        {
          return false;
        }
        else
        {
          foreach($this -> chars as $e)
          {
            if($e[$field] == $char)
            {
              $char = $e;
              break;
            }
          }
        }
      }
      
      if(!is_array($char))
      {
        die("AOChat: no valid character to login.\n");
      }

      $pq = new AOChatPacket("out", 3, $char["id"]);
      $this -> send_packet($pq);
      $pr = $this -> get_packet();
      if($pr -> type != 5)
      {
        return false;
      }

      $this -> char  = $char;
      $this -> state = "ok";

      return true;
    }
    
    function lookup_user($u)
    {
      $u = ucfirst(strtolower($u));
			if (!isset($this -> id[$u])) 
			{
				echo "*** ERROR $u inconnu***\n";
				return FALSE;
			}
      if($this -> id[$u])
      {
        return $this -> id[$u];
      }

      $this -> send_packet(new AOChatPacket("out", 21, $u));

      $cnt = 0;
      do
      {
        if($cnt ++ > 100)
        {
          return FALSE;
        }
        $this -> get_packet();
      }
      while(!$this -> id[$u]);
      
      return $this -> id[$u];
    }
    
    function lookup_group($g)
    {
      if($this -> gid[$g])
      {
        return $this -> gid[$g];
      }
      
      return false;
    }
    
    function send_tell($user, $msg, $blob = "\0")
    {
      $uid = $this -> lookup_user($user);
      if(!$uid || $uid == 0xFFFFFFFF)
      {
        return false;
      }
      
      $this -> send_packet(new AOChatPacket("out", 30, array($uid, $msg, $blob)));
      return true;
    }
    
    function send_group($group, $msg, $blob = "\0")
    {
      $gid = $this -> lookup_group($group);
      if(!$gid || empty($gid))
      {
        return false;
      }
      
      $this -> send_packet(new AOChatPacket("out", 65, array($gid, $msg, $blob)));
      return true;
    }

    function send_privgroup($group, $msg, $blob = "\0")
    {
      $this -> send_packet(new AOChatPacket("out", 57, array($group, $msg, $blob)));
      return true;
    }

    function join_privgroup($group)
    {
      $this -> send_packet(new AOChatPacket("out", 52, array($group)));
      return true;
    }
    
    function get_random_number($cap)
    {
      global $random_seeded;
      
      if($random_seeded != true)
      {
        mt_srand((double)microtime()*1000000);
        $random_seeded = true;
      }
      
      return mt_rand(0,$cap-1);
    }

    function get_random_hex_key($n)
    {
      $s = "";
      for($i=0;$i<$n;$i++)
      {
        $s = sprintf("%s%02x", $s, $this -> get_random_number(0xff));
      }
      
      return $s;
    }

    function bighexdec($x)
    {
      if(substr($x, 0, 2) != "0x")
        return $x;
      $r = "0";
      for($p = $q = strlen($x) - 1; $p >= 2; $p--)
      {
        $r = bcadd($r, bcmul(hexdec($x[$p]), bcpow(16, $q - $p)));
      }
      return $r;
    }

    function bigdechex($x)
    {
      $r = "";
      while($x != "0")
      {
        $r = dechex(bcmod($x, 16)) . $r;
        $x = bcdiv($x, 16);
      }
      return $r;
    }
    
    function bcmath_powm($x, $y, $z)
    {
      $x = $this -> bighexdec($x);
      $y = $this -> bighexdec($y);
      $z = $this -> bighexdec($z);
    
      $r = 1;
      $p = $x;
      
      while(1)
      {
        if(bcmod($y, 2))
        {
          $r = bcmod(bcmul($p, $r), $z);
          $y = bcsub($y, "1");
          if(bccomp($y, "0") == 0)
          { 
            return $this -> bigdechex($r);
          }
        }
        $y = bcdiv($y, 2);
        $p = bcmod(bcmul($p, $p), $z);
      }
    }
    
    function my_powm($x, $y, $z)
    {
      if(extension_loaded("gmp"))
      {
        return gmp_strval(gmp_powm($x, $y, $z), 16);
      }
      else if(extension_loaded("bcmath"))
      {
        return $this -> bcmath_powm($x, $y, $z);
      }
      else
      {
        die("my_powm(): no idea how to do powm...\n");
      }
    }
    
    function generate_login_key($servkey, $username, $password)
    {
      $keya = "0xeca2e8c85d863dcdc26a429a71a9815ad052f6139669dd659f98ae159d313d13c6bf2838e10a69b6478b64a24bd054ba8248e8fa778703b418408249440b2c1edd28853e240d8a7e49540b76d120d3b1ad2878b1b99490eb4a2a5e84caa8a91cecbdb1aa7c816e8be343246f80c637abc653b893fd91686cf8d32d6cfe5f2a6f";
      $keyb = "0x9c32cc23d559ca90fc31be72df817d0e124769e809f936bc14360ff4bed758f260a0d596584eacbbc2b88bdd410416163e11dbf62173393fbc0c6fefb2d855f1a03dec8e9f105bbad91b3437d8eb73fe2f44159597aa4053cf788d2f9d7012fb8d7c4ce3876f7d6cd5d0c31754f4cd96166708641958de54a6def5657b9f2e92";
      $keyc = "0x5";
      $keym = "0x" . $this -> get_random_hex_key(16);
      
      $dkey = $this -> my_powm($keyc, $keym, $keya);
      $ckey = $this -> my_powm($keyb, $keym, $keya);

      $str = sprintf("%s|%s|%s", $username, $servkey, $password);
      
      if(strlen($ckey) < 32)
        $ckey = str_repeat("0", 32-strlen($ckey)) . $ckey;
      else
        $ckey = substr($ckey, 0, 32);
        
      $prefix = pack("H16", $this -> get_random_hex_key(8));
      $length = 16 + 4 + strlen($str); // prefix, int, ...
      $pad    = str_repeat(" ", (8 - $length % 8) % 8);
      $strlen = pack("N", strlen($str));
      
      $plain   = $prefix . $strlen . $str . $pad;
      $crypted = $this -> aocrypt($ckey, $plain);
      
      return $dkey . "-" . $crypted;
    }
    
    function aocrypt($key, $str)
    {
      if(strlen($key) != 32)
      {
        return false;
      }
      
      if(strlen($str) % 8 != 0)
      {
        return false;
      }
      
      $now  = array(0, 0);
      $prev = array(0, 0);
      
      $ret  = "";

      $keyarr  = unpack("L*", pack("H*", $key));
      $dataarr = unpack("L*", $str);
      
      for($i=1; $i<=sizeof($dataarr); $i+=2)
      {
        $now[0] = $dataarr[$i]   ^ $prev[0];
        $now[1] = $dataarr[$i+1] ^ $prev[1];
        $prev   = $this -> aocrypt_permute($now, $keyarr);
        $ret   .= array_pop(@unpack("H*", pack("L*", $prev[0], $prev[1])));
      }
      
      return $ret;
    }
    
    function aocrypt_permute($x, $y)
    {
      $a = $x[0];
      $b = $x[1];
      $c = 0;
      $d = (int)0x9e3779b9;

      for($i = 32; $i-- > 0;)
      {
        $c  = (int)($c + $d);
        
        $a += ($b << 4 & -16) + $y[1] ^ $b + $c ^ ($b >> 5 & 134217727) + $y[2];
        $b += ($a << 4 & -16) + $y[3] ^ $a + $c ^ ($a >> 5 & 134217727) + $y[4];
      }

      return array($a, $b);
    }
    
  }

  global $packetmap;
  
  $packetmap = array(
    "in"  => array(
         0=>array("name"=>"Login Seed",                "args"=>"S"),
         5=>array("name"=>"Login Result OK",           "args"=>""),
         6=>array("name"=>"Login Result Error",        "args"=>"S"),
         7=>array("name"=>"Login CharacterList",       "args"=>"isii"),
        10=>array("name"=>"Client Unknown",            "args"=>"I"),
        20=>array("name"=>"Client Name",               "args"=>"IS"),
        21=>array("name"=>"Lookup Result",             "args"=>"IS"),
        30=>array("name"=>"Message Private",           "args"=>"ISS"),
        34=>array("name"=>"Message Vicinity",          "args"=>"ISS"),
        35=>array("name"=>"Message Anon Vicinity",     "args"=>"SSS"),
        36=>array("name"=>"Message System",            "args"=>"S"),
        40=>array("name"=>"Buddy Added",               "args"=>"IIS"),
        41=>array("name"=>"Buddy Removed",             "args"=>"I"),
        50=>array("name"=>"Privategroup Invited",      "args"=>"I"),
        51=>array("name"=>"Privategroup Kicked",       "args"=>"I"),
        53=>array("name"=>"Privategroup Part",         "args"=>"I"),
        55=>array("name"=>"Privategroup Client Join",  "args"=>"II"),
        56=>array("name"=>"Privategroup Client Part",  "args"=>"II"),
        57=>array("name"=>"Privategroup Message",      "args"=>"IISS"),
        60=>array("name"=>"Group Join",                "args"=>"GSIS"),
        61=>array("name"=>"Group Part",                "args"=>"G"),
        65=>array("name"=>"Group Message",             "args"=>"GISS"),
       100=>array("name"=>"Pong",                      "args"=>"S"),
       110=>array("name"=>"Forward",                   "args"=>"IM"),
      1100=>array("name"=>"Adm Mux Info",              "args"=>"iii")),
    "out" => array(
         2=>array("name"=>"Login Response GetCharLst", "args"=>"ISS"),
         3=>array("name"=>"Login Select Character",    "args"=>"I"),
        21=>array("name"=>"Name Lookup",               "args"=>"S"),
        30=>array("name"=>"Message Private",           "args"=>"ISS"),
        40=>array("name"=>"Buddy Add",                 "args"=>"IS"),
        41=>array("name"=>"Buddy Remove",              "args"=>"I"),
        42=>array("name"=>"Onlinestatus Set",          "args"=>"I"),
        50=>array("name"=>"Privategroup Invite",       "args"=>"I"),
        51=>array("name"=>"Privategroup Kick",         "args"=>"I"),
        52=>array("name"=>"Privategroup Join",         "args"=>"I"),
        54=>array("name"=>"Privategroup Kickall",      "args"=>""),
        57=>array("name"=>"Privategroup Message",      "args"=>"ISS"),
        64=>array("name"=>"Group Data Set",            "args"=>"GIS"),
        65=>array("name"=>"Group Message",             "args"=>"GSS"),
        66=>array("name"=>"Group Clientmode Set",      "args"=>"GIIII"),
        70=>array("name"=>"Clientmode Get",            "args"=>"IG"),
        71=>array("name"=>"Clientmode Set",            "args"=>"IIII"),
       100=>array("name"=>"Ping",                      "args"=>"S"),
       120=>array("name"=>"cc",                        "args"=>"s")));
  
  class AOChatPacket
  {
    function AOChatPacket($dir, $type, $data)
    {
    	if ($type=="37") return;
      global $packetmap;
      
      $this -> args = array();
      $this -> type = $type;
      $this -> dir  = $dir;
      $pmap = $packetmap[$dir][$type];
      
      if(!$pmap)
      {
        echo "Unsupported packet type (".$dir.", ".$type.")\n";
        return false;
      }
      
      if($dir == "in")
      {
        if(!is_string($data))
        {
          echo "Incorrect argument for incoming packet, expecting a string.\n";
          return false;
        }
        
        for($i=0; $i<strlen($pmap["args"]); $i++)
        {
          $sa = $pmap["args"][$i];
          switch($sa)
          {
            case "I" :
              $res  = array_pop(unpack("N", $data));
              $data = substr($data, 4);
              break;
              
            case "S" :
              $len  = array_pop(unpack("n", $data));
              $res  = substr($data, 2, $len);
              $data = substr($data, 2 + $len);
              break;
             
            case "G" :
              $res  = substr($data, 0, 5);
              $data = substr($data, 5);
              break;
              
            case "i" :
              $len  = array_pop(unpack("n", $data));
              $res  = array_values(unpack("N" . $len, substr($data, 2)));
              $data = substr($data, 2 + 4 * $len);
              break;
              
            case "s" :
              $len  = array_pop(unpack("n", $data));
              $data = substr($data, 2);
              $res  = array();
              while($len--)
              {
                $slen  = array_pop(unpack("n", $data));
                $res[] = substr($data, 2, $slen);
                $data  = substr($data, 2+$slen);
              }
              break;
              
            default :
              echo "Unknown argument type! (" . $sa . ")\n";
              continue(2);
          }
          $this -> args[] = $res;
        }
      }
      else
      {
        if(!is_array($data))
        {
          $args = array($data);
        }
        else
        {
          $args = $data;
        }
        $data = "";
        
        for($i=0; $i<strlen($pmap["args"]); $i++)
        {
          $sa = $pmap["args"][$i];
          $it = array_shift($args);
          
          if(is_null($it))
          {
            echo "Missing argument for packet.\n";
            break;
          }

          switch($sa)
          {
            case "I" :
              $data .= pack("N", $it);
              break;
            
            case "S" :
              $data .= pack("n", strlen($it)) . $it;
              break;
              
            case "G" :
              $data .= $it;
              break;

            default :
              echo "Unknown argument type! (" . $sa . ")\n";
              continue(2);
          }
        }
        
        $this -> data = $data;
      }
      return true;
    }

  }
  
?>
