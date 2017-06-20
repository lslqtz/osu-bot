<?php
/*
osu-bot was created by asd.
Project URL:https://coding.net/u/lslqtz/p/osu-bot/
*/
set_time_limit(0);
error_reporting(0);
if (PHP_SAPI !== 'cli') { die(); }
$opt=getopt('d:fm:o:t:v',array('rlt:','rgt:','only','proxy:','version','socks4-proxy:','socks5-proxy:','reapilink:','redownlink:','downcookie:','downreferer:','downuseragent:','without-proxy-getdownlink'));
if (isset($opt['v']) || isset($opt['version'])) { die("osu-bot was created by asd.\nProject URL:https://coding.net/u/lslqtz/p/osu-bot/\nVersion:1.3.\n"); }
function curl($url,$head,$followlocation,$get_effective_url,$without_postdata,$without_cookie,$without_cookiejar,$without_cookiefile,$without_timeout,$without_referer,$without_useragent,$without_proxy) {
	global $opt;
	if (!function_exists('curl_init')) { die("Error:Can't Find curl.\n"); }
	$curl=curl_init();
	curl_setopt($curl,CURLOPT_URL,$url);
	if ($head) {
		curl_setopt($curl,CURLOPT_HEADER,1);
		curl_setopt($curl,CURLOPT_NOBODY,1);
	}
	$without_timeout=($without_timeout === 1 || !isset($opt['t'])) ? 120 : $opt['t'];
	curl_setopt($curl,CURLOPT_TIMEOUT,$without_timeout);
	if ($without_postdata !== 1) {
		curl_setopt($curl,CURLOPT_POST,1);
		curl_setopt($curl,CURLOPT_POSTFIELDS,$without_postdata);
	}
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
	if ($without_cookie !== 1) {
		if ($without_cookiejar !== 1) {
			curl_setopt($curl,CURLOPT_COOKIEJAR,$without_cookiejar);
		} elseif ($without_cookiefile !== 1) {
			curl_setopt($curl,CURLOPT_COOKIEFILE,$without_cookiefile);
		} else {
			curl_setopt($curl,CURLOPT_COOKIE,$without_cookie);
		}
	}
	if (!$without_referer && isset($opt['downreferer'])) {
		curl_setopt($curl,CURLOPT_REFERER,$opt['downreferer']);
	}
	if (!$without_useragent && isset($opt['downuseragent'])) {
		curl_setopt($curl,CURLOPT_USERAGENT,$opt['downuseragent']);
	}
	if (!$without_proxy) {
		if (isset($opt['proxy'])) {
			curl_setopt($curl,CURLOPT_PROXY,$opt['proxy']);
			curl_setopt($curl,CURLOPT_PROXYTYPE,CURLPROXY_HTTP);
		} elseif (isset($opt['socks4-proxy'])) {
			curl_setopt($curl,CURLOPT_PROXY,$opt['socks4-proxy']);
			curl_setopt($curl,CURLOPT_PROXYTYPE,CURLPROXY_SOCKS4);
		} elseif (isset($opt['socks5-proxy'])) {
			curl_setopt($curl,CURLOPT_PROXY,$opt['socks5-proxy']);
			curl_setopt($curl,CURLOPT_PROXYTYPE,CURLPROXY_SOCKS5);
		}
	}
	if ($followlocation) {
		curl_setopt($curl,CURLOPT_FOLLOWLOCATION,1);
	}
	if (!$get_effective_url) {
		return curl_exec($curl);
	} else {
		curl_exec($curl);
		if ($effective_url=curl_getinfo($curl,CURLINFO_EFFECTIVE_URL)) {
			return $effective_url;
		} else {
			return 0;
		}
	}
	curl_close($curl);
	unset($url,$curl,$head,$followlocation,$effective_url,$get_effective_url,$without_postdata,$without_cookie,$without_cookiejar,$without_cookiefile,$without_timeout,$without_referer,$without_useragent,$without_proxy);
}
function getfile($url) {
	global $opt;
	$without_cookie=isset($opt['downcookie']) ? $opt['downcookie'] : 1;
	return curl($url,0,1,0,1,$without_cookie,1,1,1,0,0,0);
}
function getdlink($did) {
    global $opt;
	$without_proxy=isset($opt['without-proxy-getdownlink']) ? 1 : 0;
	if (!file_exists('cookie.txt')) {
		getcookie();
	}
	$location=curl("https://osu.ppy.sh/d/$did",1,1,1,1,0,1,'cookie.txt',1,1,1,$without_proxy);
	if ($location === 'http://osu.ppy.sh/forum/ucp.php?mode=login' || $location[0] === 'http://osu.ppy.sh/forum/ucp.php?mode=login') {
		return 0;
	} else {
		return $location;
	}
	unset($did,$without_proxy,$location);
}
function getcookie() {
	global $opt;
	global $userinfo;
	$without_proxy=isset($opt['without-proxy-getdownlink']) ? 1 : 0;
	curl('https://osu.ppy.sh/forum/ucp.php?mode=login',0,0,0,'redirect=%2F&username='.urlencode($userinfo['username']).'&password='.urlencode($userinfo['password']).'&autologin=on&login=login',0,'cookie.txt',1,1,1,1,$without_proxy);
	unset($without_proxy);
}
function set($t,$r) {
	global $opt;
	global $$t;
	switch ($opt['m']) {
		case 0:
		case 2:
			${$t}['size']=isset($r[0]) ? $r[0] : 0;
			${$t}['approach']=isset($r[1]) ? $r[1] : 0;
			${$t}['overall']=isset($r[2]) ? $r[2] : 0;
			${$t}['drain']=isset($r[3]) ? $r[3] : 0;
			${$t}['stars']=isset($r[4]) ? $r[4] : 0;
			break;
		case 1:
			${$t}['size']=0;
			${$t}['approach']=0;
			${$t}['overall']=isset($r[0]) ? $r[0] : 0;
			${$t}['drain']=isset($r[1]) ? $r[1] : 0;
			${$t}['stars']=isset($r[2]) ? $r[2] : 0;
			break;
		case 3:
			${$t}['size']=isset($r[0]) ? $r[0] : 0;
			${$t}['approach']=0;
			${$t}['overall']=isset($r[1]) ? $r[1] : 0;
			${$t}['drain']=isset($r[2]) ? $r[2] : 0;
			${$t}['stars']=isset($r[3]) ? $r[3] : 0;
			break;
	}
	unset($t,$r);
}
if (!isset($opt['m']) || !is_numeric($opt['m']) || $opt['m'] < 0 || $opt['m'] > 3) {
	$opt['m']=0;
}
if (!is_numeric($opt['d']) || !$opt['d']) {
	die("Usage:php bot.php -d [Before Days] [-v/--version Version] [-f Full Filename] [-m Mode(0:STD[Default],1:Taiko,2:CTB,3:osu!mania)] [--only] [--without-proxy-getdownlink] [--rlt/rgt=Requirement(CS:AR:OD:HP:Stars)(For Mania:CS=Keys)] [--reapilink=Replace-API-Link] [--redownlink=Replace-Download-Link] [--downcookie=Download-Cookie] [--downreferer=Download-Referer] [--downuseragent=Download-UserAgent] [--proxy=HTTP/HTTPS Proxy Address] [--socks4-proxy=Socks4 Proxy Address] [--socks5-proxy=Socks5 Proxy Address].\n");
}
echo "Enter Your osu! Username:";
$userinfo['username']=trim(fgets(STDIN));
if (empty($userinfo['username'])) {
	die("\nPlease Enter Your osu! Username.\n");
}
echo "Enter Your osu! Password:";
$userinfo['password']=trim(fgets(STDIN));
if (empty($userinfo['password'])) {
	die("\nPlease Enter Your osu! Password.\n");
}
echo "Enter Your osu! APIKey:";
$userinfo['apikey']=trim(fgets(STDIN));
if (empty($userinfo['apikey'])) {
	die("\nPlease Enter Your osu! APIKey.\n");
}
echo "Enter Your Save Dir:";
$userinfo['savedir']=trim(fgets(STDIN));
if (empty($userinfo['savedir'])) {
	die("\nPlease Enter Your Save Dir.\n");
}
if (!is_dir($userinfo['savedir']) && !mkdir($userinfo['savedir'])) {
	die("Error:Can't Create Dir.\n");
}
$apilink=isset($opt['reapilink']) ? $opt['reapilink'] : 'https://osu.ppy.sh/api/';
for ($a=$opt['d'];$a>0;$a--) {
	$beatmaps=[];
	$date=date("Y-m-d",strtotime("-$a day"));
	$args='k='.$userinfo['apikey'].'&m='.$opt['m']."&since=$date";
	echo $date.":\n";
	$beatmaps_json=json_decode(curl($apilink."get_beatmaps?$args",0,0,0,1,1,1,1,1,1,1,0));
	if (!is_array($beatmaps_json) || !isset($beatmaps_json[1])) {
		die("Error:Can't Connect osu!API Or Haven't Beatmap.\n");
	}
	if (isset($opt['rlt'])) {
		set('rlt',explode(':',$opt['rlt']));
	}
	if (isset($opt['rgt'])) {
		set('rgt',explode(':',$opt['rgt']));
	}
	for ($i=0;$i<count($beatmaps_json);$i++) {
		$rltyes=0;
		$rgtyes=0;
		if (isset($rlt)) {
			if ((!$rlt['size'] || $beatmaps_json[$i]->diff_size < $rlt['size']) && (!$rlt['approach'] || $beatmaps_json[$i]->diff_approach < $rlt['approach']) && (!$rlt['overall'] || $beatmaps_json[$i]->diff_overall < $rlt['overall']) && (!$rlt['drain'] || $beatmaps_json[$i]->diff_drain < $rlt['drain']) && (!$rlt['stars'] || $beatmaps_json[$i]->diff_difficultyrating < $rlt['stars'])) {
				$rltyes=1;
			}
		} else {
			$rltyes=1;
		}
		if (isset($rgt)) {
			if ((!$rgt['size'] || $beatmaps_json[$i]->diff_size > $rgt['size']) && (!$rgt['approach'] || $beatmaps_json[$i]->diff_approach > $rgt['approach']) && (!$rgt['overall'] || $beatmaps_json[$i]->diff_overall > $rgt['overall']) && (!$rgt['drain'] || $beatmaps_json[$i]->diff_drain > $rgt['drain']) && (!$rgt['stars'] || $beatmaps_json[$i]->diff_difficultyrating > $rgt['stars'])) {
				$rgtyes=1;
			}
		} else {
			$rgtyes=1;
		}
		if ($rltyes && $rgtyes) {
		$beatmaps[$i]=$beatmaps_json[$i]->beatmapset_id.' '.$beatmaps_json[$i]->artist.' - '.$beatmaps_json[$i]->title;
		}
		unset($rltyes,$rgtyes);
	}
	$beatmaps=array_merge(array_unique($beatmaps,SORT_NUMERIC));
	for ($i=0;$i<count($beatmaps);$i++) {
		$filename=$beatmaps[$i];
		$did=explode(' ',$beatmaps[$i])[0];
		$filename=(!isset($opt['f']) && is_numeric($did)) ? $did.'.osz' : str_replace(['/','\\',':','*','"','<','>','|','?'],'-',$filename).'.osz';
		if (!file_exists($userinfo['savedir'].'/'.$filename)) {
			if ($link=getdlink($did)) {
				if (isset($opt['redownlink'])) {
					$link=preg_replace('/http(s?):\/\/bm(\d).ppy.sh\/d\//',$opt['redownlink'],$link);
				}
				$file=getfile($link);
				if (!empty($file) && strlen($file) > 20480) {
					if (file_put_contents($userinfo['savedir'].'/'.$filename,$file,LOCK_EX)) {
						echo "Downloaded:$filename.\n";
					} else {
						echo "Error:Can't Save $filename.\n";
						if (file_exists($userinfo['savedir'].'/'.$filename)) {
							unlink($userinfo['savedir'].'/'.$filename);
						}
					}
				} else {
					echo "Error:Can't Download $filename.\n";
				}
			} else {
				echo "Error:Can't Get Download Link.\n";
			}
		}
		unset($did,$file,$link,$filename);
	}
	if (isset($opt['only'])) {
		break;
	}
	unset($args,$date,$beatmaps,$beatmaps_json);
}
unset($apilink);
?>
