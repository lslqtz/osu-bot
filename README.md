# osu-bot
### License：AGPL协议，禁止用于任何其它非商业/商业项目及网站，除已有本人许可外。申请许可请电邮或QQ：admin@acgn.xyz。
### 一键下载指定日期（或指定日期到今天）的全部Ranked歌曲。
#### 如何使用？
安装PHP（5.3及以上）并将其设进环境变量，然后进去命令提示符（cmd）到当前目录下就可以运行了。
#### 用法
Usage:php bot.php -o [Save Dir] -k [osu!API Key] -u [osu!Username] -p [osu!Password] -d [Before Days] [-f Full Filename] [-m Mode(0:STD[Default],1:Taiko,2:CTB,3:osu!mania)] [--only] [--without-proxy-getdownlink] [--reapilink=Replace-API-Link] [--redownlink=Replace-Download-Link] [--downcookie=Download-Cookie] [--downreferer=Download-Referer] [--downuseragent=Download-UserAgent] [--proxy=HTTP/HTTPS Proxy Address] [--socks4-proxy=Socks4 Proxy Address] [--socks5-proxy=Socks5 Proxy Address].
#### 参数说明
-o 保存目录
-k osu!API的Key，申请地址：[](https://osu.ppy.sh/p/api)