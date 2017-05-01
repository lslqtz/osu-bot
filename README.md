# osu-bot

Bulk download the specified date(or specified date to today) all osu! Ranked beatmap./Batch download all osu! Ranked beatmap by specified date.

## License

osu-bot is licensed under [AGPL v3.0](LICENSE).It's prohibited to use any other non-commercial/commercial items and sites,except as permitted.If you need permission,Please contact me by email,My E-Mail:admin@acgn.xyz.

## How to use?

Install PHP 5.3+,download bot.php and use command prompt.

For Windows:you can download php.zip,and run shell.bat.

### Usage
```bash
Usage:php bot.php -o [Save Dir] -k [osu!API Key] -u [osu!Username] -p [osu!Password] -d [Before Days] [-f Full Filename] [-m Mode(0:STD[Default],1:Taiko,2:CTB,3:osu!mania)] [--only] [--without-proxy-getdownlink] [--reapilink=Replace-API-Link] [--redownlink=Replace-Download-Link] [--downcookie=Download-Cookie] [--downreferer=Download-Referer] [--downuseragent=Download-UserAgent] [--proxy=HTTP/HTTPS Proxy Address] [--socks4-proxy=Socks4 Proxy Address] [--socks5-proxy=Socks5 Proxy Address].
```
### Default parameters
Default APILink=https://osu.ppy.sh/api/

Default Download Link=http(s)://bm*.ppy.sh/d/

-o Save Dir [Required]

-k osu!API Key,API Registration Link:https://osu.ppy.sh/p/api [Required]

-u osu!Username [Required]

-p osu!Password [Required]

-d Days [Required]

-f Full Filename(Default Filename：Beatmap SetID.osz)

-m Mode(0:STD[Default],1:Taiko,2:CTB,3:osu!mania)

--only Download Only The Specified Date BeatMaps.

--without-proxy-getdownlink Don't Use Proxy Login And Get Download Link(Because The Use Of Proxy Access May Cause Local Login To Fail)

--reapilink Replace API Link

--redownlink Replace Download Link

--downcookie Add Download Cookie

--downreferer Add Download Referer

--downuseragent Add Download User-Agent

--proxy Set HTTP/HTTPS Proxy

--socks4-proxy Set Socks4 Proxy

--socks5-proxy Set Socks5 Proxy
