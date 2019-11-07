# Chat

#### 介绍
Chat是基于hyperf搭建的一个聊天系統

#### 安装教程
    1. git clone https://github.com/qiuapeng921/chat.git
    2. .env修改mysql和redis配置
    
#### 使用说明
    1. make install 初始化项目
    2. make start 启动项目

### 登陆
     1. 分别生成app和web的 token
     
### onOpen
     1. 连接socket 请求头带token来区分用户和设配端
     2. onOpen验证token 判断是否为协程客户端链接 协程客户端token为system
     3. 解析token得到用户id
     4. 获取本机 物理机ip和 socket端口和 本次请求的fd
     5. 将用户id 和 ip+端口+fd 绑定 存入redis
     6. 通过解析token得到本次链接是app还是web 存入 class属性
  
### onMessage

    1. 获取消息 判断是心跳还是消息体 心跳则直接返回PONG
    2. 解析消息体 判断是否为json格式
    3. 通过消息体内容反射加载类 处理交由类处理
###### 发送消息
    1.  
 
 ### onClose
     1. 获取fd详情信息
     2. 判断status是否断开
     3. 判断fd详情中获取fd绑定的uid
     4. 删除uid 绑定的设配端fd信息