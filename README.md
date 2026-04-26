![](https://img.shields.io/badge/web安全-靶场-PTEST)
![](https://img.shields.io/badge/version-1.0-success)
![](https://img.shields.io/github/stars/zhuifengshaonianhanlu/pikachu.svg)
![](https://img.shields.io/github/forks/zhuifengshaonianhanlu/pikachu.svg)
![](https://img.shields.io/github/license/zhuifengshaonianhanlu/pikachu.svg)

“如果你想搞懂一个漏洞，比较好的方法是：你可以自己先制造出这个漏洞（用代码编写），然后再利用它，最后再修复它。”

<br>

# pikachu

Pikachu 是一个带有漏洞的 Web 应用系统，这里收录了常见的 Web 安全漏洞。如果你正在学习 Web 渗透测试，或者想快速搭建一个适合练习的漏洞环境，那么 Pikachu 可以作为一个轻量、直接的练习平台。<br>

## 安全声明

* 本项目仅用于本地学习、授权测试和安全研究<br>
* 不建议部署到公网<br>
* 不要在生产环境运行<br>
* Docker Lab 或任何漏洞环境都应只绑定本地地址 `127.0.0.1`<br>
* 使用者应自行承担在非授权环境中使用造成的风险<br>

## Pikachu 上的漏洞类型如下：<br>

* Brute Force(暴力破解漏洞)<br>
* XSS(跨站脚本漏洞)<br>
* CSRF(跨站请求伪造)<br>
* SQL-Inject(SQL 注入漏洞)<br>
* RCE(远程命令/代码执行)<br>
* Files Inclusion(文件包含漏洞)<br>
* Unsafe file downloads(不安全的文件下载)<br>
* Unsafe file uploads(不安全的文件上传)<br>
* Over Permission(越权漏洞)<br>
* ../../../(目录遍历)<br>
* I can see your ABC(敏感信息泄露)<br>
* PHP 反序列化漏洞<br>
* XXE(XML External Entity attack)<br>
* 不安全的 URL 重定向<br>
* SSRF(Server-Side Request Forgery)<br>
* JWT(JSON Web Token)<br>
* Host Header<br>
* Session Fixation(会话固定)<br>
* CORS Misconfiguration(CORS 配置错误)<br>
* Clickjacking(点击劫持)<br>
* 管理工具<br>
* More...(找找看？还有彩蛋)<br>

管理工具中提供了一个简易的 XSS 管理后台，方便做钓鱼、Cookie 窃取等练习。<br>
每个漏洞模块都尽量配了小场景，点击页面右上角的“提示”可以查看辅助说明。<br>

## 如何安装和使用

Pikachu 使用 PHP 开发，数据库使用 MySQL，因此运行前需要准备好 “PHP + MySQL + Web 中间件（如 Apache、Nginx）” 的基础环境。<br>
建议在测试环境中直接使用 XAMPP、WAMP 等集成环境搭建。接下来：<br>
-->把下载下来的 `pikachu` 文件夹放到 Web 服务根目录下；<br>
-->根据实际情况修改 `inc/config.inc.php` 里的数据库连接配置；<br>
-->访问 `http://x.x.x.x/pikachu`，如果看到红色提示“欢迎使用，pikachu 还没有初始化，点击进行初始化安装！”，点击即可完成安装。<br>

## 新增模块

* JWT：演示 `alg=none`、token 篡改、权限字段盲信等问题<br>
* Host Header：演示 Host 头污染导致重置链接或绝对链接被污染<br>
* Session Fixation：演示登录后未刷新 Session ID 导致会话固定<br>
* CORS Misconfiguration：演示 Origin 反射、Allow-Credentials 误用<br>
* Clickjacking：演示页面缺少 `X-Frame-Options` / `CSP frame-ancestors` 导致可被 iframe 嵌套<br>

## Docker Lab / 靶场编排中心

Docker Lab 当前已完成 **Phase 1 骨架**，但还不是完整编排中心。<br>
当前源码仅包含以下只读能力：<br>
* Docker 环境检查<br>
* 白名单模板加载<br>
* 模板列表页<br>
* 只读容器状态展示<br>
* 白名单模板容器日志查看<br>
<br>
以下能力**当前尚未开放**：<br>
* 启动容器<br>
* 停止容器<br>
* 删除容器<br>
* 重启容器<br>
* 任意 Docker 命令执行<br>
<br>
第一版规划目标是在 Pikachu 页面中管理少量白名单漏洞容器模板，例如 Redis、MySQL、Flask 等。当前设计约束包括：<br>
* 第一批模板：Redis 未授权、MySQL 弱口令、Flask SSTI<br>
* 只允许白名单模板，不允许用户自定义任意 `image`、`command`、`volume`、`privileged`<br>
* 不允许任意 Docker 命令执行<br>
* 只管理带 `pikachu.lab=true` label 的容器<br>
* 默认端口仅绑定 `127.0.0.1`<br>
* 禁止 `privileged` / `volume` / host network<br>

## 使用 Docker 运行 Pikachu

使用已有镜像：

```powershell
docker run -d -p 127.0.0.1:8765:80 8023/pikachu-expect:latest
```

本地构建：

```powershell
docker build -t pikachu .
docker run -d -p 127.0.0.1:8080:80 pikachu
```

说明：以上命令默认只绑定本机回环地址，更适合 Windows / PowerShell 本地练习环境。<br>

## 切记

“少就是多，慢就是快”

## WIKI

[点击进入](https://github.com/zhuifengshaonianhanlu/pikachu/wiki/01:%E6%89%AF%E5%9C%A8%E5%89%8D%E9%9D%A2)
