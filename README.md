# clmts-git
clmts微信打卡

## 数据库设计
### login（用户打卡记录）
| 字段        | 含义       | 类型         | 备注         |
| ----------- | ---------- | ------------ | ------------ |
| id          | 标识码     | int(11)      | PK;AI        |
| openid      | 微信标识码 | varchar(50)  | NN           |
| date        | 打卡日期   | datetime     | NN           |
| name        | 姓名       | varchar(255) | NN           |
| phone       | 联系电话   | varchar(255) | NN           |
| address     | 住址       | varchar(255) | NN           |
| temperature | 体温       | decimal(5,2) | NN           |
| sign        | 进出标志   | tinyint(4)   | 0-出;1-进;NN |

### volunteer（志愿者注册）

| 字段    | 含义       | 类型         | 备注 |
| ------- | ---------- | ------------ | ---- |
| openid  | 微信标识码 | varchar(255) | PK   |
| name    | 姓名       | varchar(255) | NN   |
| id      | 身份证号码 | varchar(255) | NN   |
| gender  | 性别       | tinyint(1)   | NN   |
| birth   | 出生日期   | date         | NN   |
| phone   | 联系电话   | varchar(255) | NN   |
| address | 住址       | varchar(255) | NN   |




## 文件描述
#### config.php
微信公众平台的appid/appsecret/token.  
数据库链接配置.  
输出到浏览器控制台和重定向的两个简单函数.

#### create-qrcode.php
创建参数二维码

#### database.php
数据库连接类  
protected connect 连接数据库  
public todayRecord 判断今天是否有出入记录  
public addRecord 保存打卡记录  
public searchLatest 搜索最新出入记录  
public searchVolunteer 搜索志愿者信息  
public addVolunteer 保存志愿者信息  

#### index.php
微信公众平台服务器url

#### menu_class.php
菜单操作类

#### menu.php
创建两个一级菜单：点击扫码、志愿者

#### message.php
构造微信消息
clock_diagram_text() 进出打卡链接图文消息  
volunteer_diagram_text() 志愿者注册图文信息

#### oauth.php
oauth2.0授权

#### record.php
处理表单数据，根据state参数区分打卡/注册.

#### register.html
志愿者信息展示/提交页面，若已有志愿者信息，将会禁止修改、提交.

#### success.html
提交成功页面

#### upload.html
填表页面

#### warm.html
操作异常页面，通过msg参数输出错误提示.