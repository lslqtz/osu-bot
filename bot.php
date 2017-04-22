<?php
/*
osu-bot was created by asd.
Project URL:https://coding.net/u/lslqtz/p/osu-bot/
*/
set_time_limit(0);
error_reporting(0);
if (PHP_SAPI !== 'cli') { die(); }
$opt=getopt('d:fk:m:o:p:t:u:',array('only','proxy:','socks4-proxy:','socks5-proxy:','reapilink:','redownlink:','downcookie:','downreferer:','downuseragent:','without-proxy-getdownlink'));
function curl($url,$head,$followlocation,$get_effective_url,$without_postdata,$without_cookie,$without_cookiejar,$without_cookiefile,$without_timeout,$without_referer,$without_useragent,$without_proxy) {
	global $opt;
	$curl=curl_init();
	curl_setopt($curl,CURLOPT_URL,$url);
	if ($head) {
		curl_setopt($curl,CURLOPT_HEADER,1);
		curl_setopt($curl,CURLOPT_NOBODY,1);
	}
	if ($without_timeout === 1 || !isset($opt['t'])) {
		$without_timeout=120;
	} else {
		$without_timeout=$opt['t'];
	}
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
			curl_setopt($curl,CURLOPT_HTTPHEADER,array('Cookie:'.$without_cookie));
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
	unset($url,$curl,$head,$code,$followlocation,$effective_url,$get_effective_url,$without_postdata,$without_cookie,$without_cookiejar,$without_cookiefile,$without_timeout,$without_referer,$without_useragent,$without_proxy);
}
function getfile($url) {
	global $opt;
	$without_cookie=1;
	if (isset($opt['downcookie'])) {
		$without_cookie=$opt['downcookie'];
	}
	return curl($url,0,1,0,1,$without_cookie,1,1,1,0,0,0);
	unset($url,$without_cookie);
}
function getdlink($did) {
	$without_proxy=0;
	if (isset($opt['without-proxy-getdownlink'])) {
		$without_proxy=1;
	}
	if (!file_exists('cookie.txt')) {
		getcookie();
	}
	$location=curl("https://osu.ppy.sh/d/$did",1,1,1,1,0,1,'cookie.txt',1,1,1,$without_proxy);
	if ($location === 'http://osu.ppy.sh/forum/ucp.php?mode=login' || $location[0] === 'http://osu.ppy.sh/forum/ucp.php?mode=login') {
		return 0;
	} else {
		return $location;
	}
	unset($did,$cookie,$without_proxy,$location);
}
function getcookie() {
	global $opt;
	curl('https://osu.ppy.sh/forum/ucp.php?mode=login',0,0,0,'redirect=%2F&username='.urlencode($opt['u']).'&password='.urlencode($opt['p']).'&autologin=on&login=login',0,'cookie.txt',1,1,1,1,0);
}
if (!isset($opt['m']) || !is_numeric($opt['m']) || $opt['m'] < 0 || $opt['m'] > 3) {
	$opt['m']=0;
}
if (!isset($opt['o'],$opt['d'],$opt['k'],$opt['u'],$opt['p']) || !is_numeric($opt['d']) || !$opt['d']) {
	die("Usage:php bot.php -o [Save Dir] -k [osu!API Key] -u [osu!Username] -p [osu!Password] -d [Before Days] [-f Full Filename] [-m Mode(0:STD[Default],1:Taiko,2:CTB,3:osu!mania)] [--only] [--without-proxy-getdownlink] [--reapilink=Replace-API-Link] [--redownlink=Replace-Download-Link] [--downcookie=Download-Cookie] [--downreferer=Download-Referer] [--downuseragent=Download-UserAgent] [--proxy=HTTP/HTTPS Proxy Address] [--socks4-proxy=Socks4 Proxy Address] [--socks5-proxy=Socks5 Proxy Address].\n");
}
if (!is_dir($opt['o'])) {
	if (!mkdir($opt['o'])) {
		die("Error:Can't Create Dir.\n");
	}
}
for ($a=$opt['d'];$a>0;$a--) {
	$beatmaps=[];
	$args='k='.$opt['k'].'&m='.$opt['m'].'&since='.date("Y-m-d",strtotime("-$a day"));
	if (isset($opt['reapilink'])) {
		$apilink=$opt['reapilink'];
	} else {
		$apilink='https://osu.ppy.sh/api/';
	}
	$beatmaps_json=json_decode(curl($apilink."get_beatmaps?$args",0,0,0,1,1,1,1,1,1,1,0));
	if (!isset($beatmaps_json[0])) {
		die("Error:Can't Connect osu!API Or Haven't Beatmap.\n");
	}
	for ($i=0;$i<count($beatmaps_json);$i++) {
		$beatmaps[$i]=$beatmaps_json[$i]->beatmapset_id.' '.$beatmaps_json[$i]->artist.' - '.$beatmaps_json[$i]->title;
	}
	$beatmaps=array_merge(array_unique($beatmaps,SORT_NUMERIC));
	for ($i=0;$i<count($beatmaps);$i++) {
		$filename=$beatmaps[$i];
		$did=explode(' ',$beatmaps[$i])[0];
		if (!isset($opt['f']) && is_numeric($did)) {
			$filename=$did.'.osz';
		} else {
			$filename.='.osz';
		}
		if (!file_exists($opt['o'].'/'.$filename)) {
			if ($link=getdlink($did)) {
				if (isset($opt['redownlink'])) {
					$link=preg_replace('/http(s?):\/\/bm(\d).ppy.sh\/d\//',$opt['redownlink'],$link);
				}
				$file=getfile($link);
				if (!empty($file) && strlen($file) > 20480) {
					if (file_put_contents($opt['o'].'/'.$filename,$file,LOCK_EX)) {
						echo "Downloaded:$filename.\n";
					} else {
						echo "Error:Can't Save $filename.\n";
						if (file_exists($opt['o'].'/'.$filename)) {
							unlink($opt['o'].'/'.$filename);
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
	unset($args,$apilink,$beatmaps,$beatmaps_json);
}
?>
