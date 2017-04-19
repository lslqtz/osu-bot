# osu-bot
### License：AGPL协议，禁止用于任何其它非商业/商业项目及网站，除已有本人许可外。申请许可请电邮或QQ：admin@acgn.xyz。
### 一键下载指定日期（或指定日期到今天）的全部Ranked歌曲。
#### 如何使用？
安装PHP（5.3及以上）并将其设进环境变量，然后进去命令提示符（cmd）到当前目录下就可以运行了。
#### 用法
Usage:php bot.php -o [Save Dir] -k [osu!API Key] -u [osu!Username] -p [osu!Password] -d [Before Days] [-f Full Filename] [-m Mode(0:STD[Default],1:Taiko,2:CTB,3:osu!mania)] [--only] [--without-proxy-getdownlink] [--reapilink=Replace-API-Link] [--redownlink=Replace-Download-Link] [--downcookie=Download-Cookie] [--downreferer=Download-Referer] [--downuseragent=Download-UserAgent] [--proxy=HTTP/HTTPS Proxy Address] [--socks4-proxy=Socks4 Proxy Address] [--socks5-proxy=Socks5 Proxy Address].
#### 参数说明
-o 保存目录
-k osu!API的Key，申请地址：https://osu.ppy.sh/p/api
-u osu!账号
-p osu!密码
-d 从距离今天起后退多少天
-f 全文件名（默认文件名：数字ID.osz）
-m 模式（0:STD[Default],1:Taiko,2:CTB,3:osu!mania）
--only 仅仅下载指定日期的图，不包括其它日期
--without-proxy-getdownlink 禁止使用代理获取下载链接（因为在代理上模拟登录osu!可能造成本地的osu!登录失效）
--reapilink 替换API链接
--redownlink 替换下图链接
--downcookie 添加下图Cookie（例如bloodcat需要）
--downreferer 添加下图Referer（例如mengsky需要）
--downuseragent 添加下图UA（例如mengsky需要）
--proxy 设置HTTP/HTTPS代理
--socks4-proxy 设置socks4代理
--socks5-proxy 设置socks5代理