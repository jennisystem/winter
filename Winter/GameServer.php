<?php
include "Winter.php";
$cp = new Winter("GameConf1.xml");
$cp->init();
$cp2 = new Winter("GameConf2.xml");
$cp2->init();
while(true){
	$cp->loopFunction();
	$cp2->loopFunction();
}
function handleCommand(&$user, $msg, &$server){
	$arr = explode(" ", substr($msg, 1), 2);
	$cmd = $arr[0];
	if(count($arr) == 2)
	$arg = $arr[1];
	else
	$arg = "";
	$cmd = strtolower($cmd);
	$configPath = "../Config/";
	$modPath = "../Mod/";
	include $configPath . "Config.php";
	include $modPath . "Patched.php";
	include $modPath . "Modrooms.php";
	global $mysqlpass, $mysqluser, $mysqlhost, $database, $table, $patched, $modrooms;
	$id = $user->getID();
	$email = $user->getEmail();
	$conn = mysqli_connect($mysqlhost,$mysqluser,$mysqlpass);
	$nick = $user->username;
	if($cmd == "ping"){
	$user->sendPacket("%xt%sm%-1%0%Pong%");
	}
	if($cmd == "global" && $user->getRank() >= 5){
	$server->sendPacket("%xt%sm%-1%0%$arg%");
        sleep(2);
	$server->sendPacket("%xt%sm%-1%0%$arg%");
        sleep(2);
	$server->sendPacket("%xt%sm%-1%0%$arg%");
	    sleep(2);
	$server->sendPacket("%xt%sm%-1%0%$arg%");
	    sleep(2);
	$server->sendPacket("%xt%sm%-1%0%$arg%");
	}
	if($cmd == "addbeta" && $user->getRank() >= 5){
	foreach($server->users as $i=>$suser){
        if($suser->getName() == $arg){
	    $suser->addItem(413);
	        }
	    }
	}
    if($cmd == "ai") {
	if(in_array($arg, $patched)) {
		if(!$user->isModerator) {
			$user->sendPacket("%xt%e%-1%402%%");
		} else {
			$user->addItem($arg);
		}
	} else {
		$user->addItem($arg);
	}
}
    if($cmd == "jr") {
	if(in_array($arg, $modrooms)) {
		if(!$user->isModerator) {
		$user->sendPacket("%xt%e%-1%610%Sorry, this is a moderator-only room. This is not a ban.%");
    } else {
		$user->joinRoom($arg);
		}
	} else {
		$user->joinRoom($arg);
	}
}
	
	if($cmd == "id"){
	$user->sendPacket("%xt%sm%-1%0%Your ID is $id%");
	}
	if($cmd == "pin"){
	$user->setPin($arg);
	}
	if($cmd == "summon" && $user->isModerator){
	foreach($server->users as $i=>$suser){
		if($suser->getName() == $arg){
				$suser->joinRoom($user->room, 330, 300);
            }
		}
	}
	if($cmd == "goto"){
	foreach($server->users as $i=>$suser){
		if($suser->getName() == $arg){
				$user->joinRoom($suser->room, 330, 300);
            }
		}
	}
	if($cmd == "addall"){
    global $crumbs;
    foreach(array_keys($crumbs) as $item)
   {
    $user->addFreeItem($item);
	unset($user);
        }
    }
	if($cmd == "move" && $user->isModerator){
	$server->sendPacket("%xt%sp%-1%0%" . join("%", explode(" ", $arg)));
	}
	if($cmd == "close" && $user->isModerator){
	if($arg == null)
		$server->sendPacket("%xt%lm%-1%%");
	}
	if($cmd == "ac" && $arg <= 5000){
		$user->setCoins($user->getCoins() + $arg);
	}
    if($cmd == "reboot" && $user->getRank() == 6){
		foreach($server->users as $i=>$suser){
		    $suser->sendPacket("%xt%e%-1%990%");
			socket_close($suser->sock);
			unset($server->users[$i]);
		}
		die();
	}
		if($cmd == "ban" && $user->isModerator){ //Permanent ban
			foreach($server->users as $i=>$suser){
		        if($suser->getName() == $arg){
                $conn->select($database);
				$conn->query("UPDATE $table SET ubdate='PERMABANNED' WHERE username='$arg'");
				$suser->sendPacket("%xt%e%-1%610%Your account has been permanently banned by " . $user->username . ".%");
				$server->sendPacket("%xt%sm%-1%0%$user->username has banned " . $arg . "");
				
	        } else {
			    $conn->select("$database", $con);
				$conn->query("UPDATE $table SET ubdate='PERMABANNED' WHERE username='$arg'");
				$server->sendPacket("%xt%sm%-1%0%$user->username has banned " . $arg . "");
				
	        }
	    }
	}
		if($cmd == "1ban" && $user->isModerator){ //1 day ban
			foreach($server->users as $i=>$suser){
		        if($suser->getName() == $arg){
                $conn->select("$database", $con);
				$conn->query("UPDATE $table SET ubdate='" . strtotime("+ 1 day") . "' WHERE username='$arg'");
				$suser->sendPacket("%xt%e%-1%610%Your account has been temporarily banned by " . $user->username . ". This ban will last 24 hours.%");
				$server->sendPacket("%xt%sm%-1%0%$user->username has banned " . $arg . "");
				
	        } else {
			    $conn->select("$database", $con);
				$conn->query("UPDATE $table SET ubdate='" . strtotime("+ 1 day") . "' WHERE username='$arg'");
				$server->sendPacket("%xt%sm%-1%0%$user->username has banned " . $arg . "");
				
	        }
	    }
	}
		if($cmd == "2ban" && $user->isModerator){ //2 day ban
			foreach($server->users as $i=>$suser){
		        if($suser->getName() == $arg){
                $conn->select("$database", $con);
				$conn->query("UPDATE $table SET ubdate='" . strtotime("+ 2 days") . "' WHERE username='$arg'");
				$suser->sendPacket("%xt%e%-1%610%Your account has been temporarily banned by " . $user->username . ". This ban will last 48 hours.%");
				$server->sendPacket("%xt%sm%-1%0%$user->username has banned " . $arg . "");
				
	        } else {
			    $conn->select("$database", $con);
				$conn->query("UPDATE $table SET ubdate='" . strtotime("+ 2 days") . "' WHERE username='$arg'");
				$server->sendPacket("%xt%sm%-1%0%$user->username has banned " . $arg . "");
				
	        }
	    }
	}
		if($cmd == "3ban" && $user->isModerator){ //3 day ban
			foreach($server->users as $i=>$suser){
		        if($suser->getName() == $arg){
                $conn->select("$database", $con);
				$conn->query("UPDATE $table SET ubdate='" . strtotime("+ 3 days") . "' WHERE username='$arg'");
				$suser->sendPacket("%xt%e%-1%610%Your account has been temporarily banned by " . $user->username . ". This ban will last 72 hours.%");
				$server->sendPacket("%xt%sm%-1%0%$user->username has banned " . $arg . "");
				
	        } else {
			    $conn->select("$database", $con);
				$conn->query("UPDATE $table SET ubdate='" . strtotime("+ 3 days") . "' WHERE username='$arg'");
				$server->sendPacket("%xt%sm%-1%0%$user->username has banned " . $arg . "");
				
	        }
	    }
	}

	    if($cmd == "kick" && $user->isModerator){
			foreach($server->users as $i=>$suser){
		        if($suser->getName() == $arg){
				$suser->sendPacket("%xt%e%-1%610%You have been kicked by " . $user->username . ". This is not a ban%");
	        }
	    }
	}
		  if($cmd == "nick" && $user->getRank() >= 4){
		  		$conn->select("$database", $con);
				$conn->query("UPDATE $table SET nickname = '$arg' WHERE id = '$id'");
				
            }
	    if($cmd == "unban" && $user->isModerator){
				$conn->select("$database", $con);
                $conn->query("UPDATE $table SET ubdate = '' WHERE username = '$arg'");
				$server->sendPacket("%xt%sm%-1%0%$user->username has unbanned " . $arg . "");
				
            }
		   if($cmd == "accessadd" && $user->getRank() >= 5){
		        $conn->select("$database", $con);
                $conn->query("UPDATE $table SET ismoderator = '1' WHERE username = '$arg'");
				$conn->query("UPDATE $table SET rank = '4' WHERE username = '$arg'");
				$server->sendPacket("%xt%sm%-1%0%$user->username has made $arg a moderator!%");
				
        }
		   if($cmd == "accessdel" && $user->getRank() >= 5){
		   	    $conn->select("$database", $con);
                $conn->query("UPDATE $table SET ismoderator = '0' WHERE username = '$arg'");
				$conn->query("UPDATE $table SET rank = '1' WHERE username = '$arg'");
				$server->sendPacket("%xt%sm%-1%0%$user->username removed $arg from the access list!%");
				
        }
            if($cmd == "clone" && $user->getRank() >= 4){
	          foreach($server->users as $i=>$suser){
		      if($suser->getName() == $arg){
			  $user->setColour($suser->getColour());
			  $user->setHead($suser->getHead());
			  $user->setFace($suser->getFace());
			  $user->setNeck($suser->getNeck());
			  sleep(1);
			  $user->setBody($suser->getBody());
			  $user->setHands($suser->getHands());
			  sleep(1);
			  $user->setFeet($suser->getFeet());
			  $user->setPin($suser->getPin());
			  sleep(1);
			  $user->setPhoto($suser->getPhoto());
			}
		}	
	}
	        if($cmd == "me" && $user->getRank() >= 4){
	        foreach($server->users as $i=>$suser){
		      if($suser->getName() == $arg){
			  $suser->setColour($user->getColour());
			  $suser->setHead($user->getHead());
			  $suser->setFace($user->getFace());
			  $suser->setNeck($user->getNeck());
			  sleep(1);
			  $suser->setBody($user->getBody());
			  $suser->setHands($user->getHands());
			  sleep(1);
			  $suser->setFeet($user->getFeet());
			  $suser->setPin($user->getPin());
			  sleep(1);
			  $suser->setPhoto($user->getPhoto());
			}
		}	
	}
	if($cmd == "open" && $user->isModerator){
		$server->sendPacket("%xt%lm%-1%$arg%");
	}
    if($cmd == "msg" && $user->isModerator){
		$server->sendPacket("%xt%lm%-1%http://ultimatecheatscp.info/content/global.swf?msg=$arg%");
	}
	if($cmd == "up"){
  switch(strtoupper($arg)){
      case 'ROCKHOPPER': {
          $clothes = '5|442|152|161|0|5020|0|0|0';
      }
      break;
      case 'H4X0R': {
          $clothes = '14|1201|0|3061|4282|0|6057|0|0';
      }
      break;
      case 'WATER SENSEI': {
          $clothes = '14|1107|2009|0|4281|0|0|0|0';
      }
      break;
      case 'AUNT ARCTIC': {
          $clothes = '2|1044|2007|0|0|0|0|0|0';
      }
      break;
      case 'GARY': {
          $clothes = '1|0|115|4022|0|0|0|0|0';
      }
      break;
      case 'CADENCE': {
          $clothes = '10|1032|0|3011|0|5023|1033|0|0';
      }
      break;
      case 'FRANKY': {

          //'1|1000|0|0|0|234|6000|0|0';
          $clothes = '7|1000|0|0|0|5024|6000|0|0';
      }
      break;
      case 'PETEY K': {
          $clothes = '2|1003|2000|3016|0|0|0|0|0';
      }
      break;
      case 'G BILLY': {
          $clothes = '1|1001|0|0|0|5000|0|0|0';
      }
      break;
      case 'STOMPIN BOB': {
          $clothes = '5|1002|101|0|0|5025|0|0|0';
      }
      break;
      case 'SENSEI': {
          $clothes = '14|1068|2009|0|0|0|0|0|0';
      }
      break;
      case 'FIRE SENSEI': {
          $clothes = '14|1107|2015|0|4148|0|0|0|0';
      }
      break;
	  case 'ZKID': {
		  $clothes = '1|674|104|173|281|322|352|550|906';
	  }
      break;
      case 'BILLYBOB': {
          $clothes = '1|405|0|0|280|328|352|500|0';
      }
      break;
      case 'GIZMO': {
          $clothes = '1|405|0|173|221|0|0|0|0';
      }
      break;
      case 'RSNAIL': {
          $clothes = '12|452|0|0|0|0|0|0|0';
      }
      break;
      case 'SCREENHOG': {
          $clothes = '5|403|0|0|0|0|0|0|0';
      }
      break;
      case 'HAPPY77': {
          $clothes = '5|452|131|0|212|0|0|500|0';
      }
      break;
      default:
      return;
  }
  $clothes = explode("|", $clothes);
  $user->setColour($clothes[0]);
  $user->setHead($clothes[1]);
  $user->setFace($clothes[2]);
  $user->setNeck($clothes[3]);
  $user->setBody($clothes[4]);
  $user->setHands($clothes[5]);
  $user->setFeet($clothes[6]);
  $user->setPin($clothes[7]);
  $user->setPhoto($clothes[8]);
  }
  if($cmd == "botup" && $user->isModerator){
  switch(strtoupper($arg)){
      case 'ROCKHOPPER': {
          $clothes = '5|442|152|161|0|5020|0|0|0';
      }
      break;
      case 'H4X0R': {
          $clothes = '14|1201|0|3061|4282|0|6057|0|0';
      }
      break;
      case 'WATER SENSEI': {
          $clothes = '14|1107|2009|0|4281|0|0|0|0';
      }
      break;
      case 'AUNT ARCTIC': {
          $clothes = '2|1044|2007|0|0|0|0|0|0';
      }
      break;
      case 'GARY': {
          $clothes = '1|0|115|4022|0|0|0|0|0';
      }
      break;
      case 'CADENCE': {
          $clothes = '10|1032|0|3011|0|5023|1033|0|0';
      }
      break;
      case 'FRANKY': {

          //'1|1000|0|0|0|234|6000|0|0';
          $clothes = '7|1000|0|0|0|5024|6000|0|0';
      }
      break;
      case 'PETEY K': {
          $clothes = '2|1003|2000|3016|0|0|0|0|0';
      }
      break;
      case 'G BILLY': {
          $clothes = '1|1001|0|0|0|5000|0|0|0';
      }
      break;
      case 'STOMPIN BOB': {
          $clothes = '5|1002|101|0|0|5025|0|0|0';
      }
      break;
      case 'SENSEI': {
          $clothes = '14|1068|2009|0|0|0|0|0|0';
      }
      break;
      case 'FIRE SENSEI': {
          $clothes = '14|1107|2015|0|4148|0|0|0|0';
      }
      break;
	  case 'ZKID': {
		  $clothes = '1|674|104|173|281|322|352|550|906';
	  }
      break;
      case 'BILLYBOB': {
          $clothes = '1|405|0|0|280|328|352|500|0';
      }
      break;
      case 'GIZMO': {
          $clothes = '1|405|0|173|221|0|0|0|0';
      }
      break;
      case 'RSNAIL': {
          $clothes = '12|452|0|0|0|0|0|0|0';
      }
      break;
      case 'SCREENHOG': {
          $clothes = '5|403|0|0|0|0|0|0|0';
      }
      break;
      case 'HAPPY77': {
          $clothes = '5|452|131|0|212|0|0|500|0';
      }
      break;
      default:
          return;
  }
  $clothes = explode("|", $clothes);
  $server->sendPacket("%xt%upc%-1%0%" . $clothes[0] . "%");
  $server->sendPacket("%xt%uph%-1%0%" . $clothes[1] . "%");
  $server->sendPacket("%xt%upf%-1%0%" . $clothes[2] . "%");
  $server->sendPacket("%xt%upn%-1%0%" . $clothes[3] . "%");
  $server->sendPacket("%xt%upb%-1%0%" . $clothes[4] . "%");
  $server->sendPacket("%xt%upa%-1%0%" . $clothes[5] . "%");
  $server->sendPacket("%xt%upe%-1%0%" . $clothes[6] . "%");
  $server->sendPacket("%xt%upl%-1%0%" . $clothes[7] . "%");
  $server->sendPacket("%xt%upp%-1%0%" . $clothes[8] . "%");
}
}
?>