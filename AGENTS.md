# Repository Guidelines

## 项目结构与模块组织
仓库是一个基于 `PHP + MySQL` 的漏洞练习平台。首页与公共布局位于根目录：`index.php`、`header.php`、`footer.php`、`install.php`。公共配置和函数放在 `inc/`，包括数据库连接、验证码、上传和通用辅助函数。漏洞模块集中在 `vul/`，按类别拆分目录，例如 `vul/sqli/`、`vul/xss/`、`vul/jwt/`。静态资源位于 `assets/`，XSS 管理后台在 `pkxss/`，示例与杂项文件在 `test/`、`wiki/`。

## 构建、测试与开发命令
本地开发以 PHP 运行环境为主，不依赖 `npm` 或 Composer。

```powershell
php -S 127.0.0.1:8080
```

在当前目录启动内置服务器，适合快速查看页面。

```powershell
php -l .\index.php
php -l .\vul\jwt\jwt_login.php
```

执行 PHP 语法检查；修改页面或模块后至少检查受影响文件。

```powershell
docker build -t pikachu .
docker run -p 80:80 pikachu
```

按仓库内 `Dockerfile` 构建和运行容器环境。

## 代码风格与命名约定
沿用现有 PHP 风格：4 空格缩进，文件名小写，模块目录使用语义化名称，例如 `sessionfixation`、`hostheader`。新增页面优先保持同目录命名模式，如 `xxx.php`、`xxx_login.php`、`xxx_admin.php`。避免大规模重构公共文件；优先做最小范围修改，保持教学漏洞逻辑可复现。

## 测试要求
仓库当前没有 PHPUnit 测试套件，不要编造测试命令。提交前至少完成三项检查：`php -l` 语法检查、页面入口手工访问、受影响漏洞链路手工复现。若修改登录、跳转、Cookie、Session 或响应头，需额外验证输出顺序和浏览器行为。

## 提交与 Pull Request 要求
历史提交同时存在英文短句和中文说明，建议继续使用“简短祈使句 + 明确范围”，例如 `fix jwt cookie path` 或 `修复 cors 演示页说明`。PR 需要写清变更模块、复现步骤、验证方式；若改动页面交互或教学文案，附截图更合适。不要把压缩包、环境缓存或临时文件一并提交，例如 `pikachu.7z`、`.DS_Store`。

## 安全与配置提示
这是故意保留漏洞的靶场，不要把练习模块改造成安全产品。配置变更优先放在本地环境或容器中验证；数据库连接以 `inc/config.inc.php` 为准，初始化流程通过 `install.php` 完成。
