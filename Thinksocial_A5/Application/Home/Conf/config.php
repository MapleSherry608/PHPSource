<?php
return array(
    /* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__HOME_CSS__'=>__ROOT__.'/Public/Home/Css',//在服务器上改成""
        '__HOME_IMG__'=>__ROOT__.'/Public/Home/Images',//在服务器上改成"."
        '__HOME_JS__'=>__ROOT__.'/Public/Home/Js',//在服务器上改成""
        '__STATIC__'=>__ROOT__.'/Public/Static',//在服务器上改成""
        '__HOME_REFUEL__'=>__ROOT__.'/Public/Home/Refuel',//在服务器上改成""
        '__HOME_PUNCH__'=>__ROOT__.'/Public/Home/punchcard',//在服务器上改成""
        '__HOME_ACTIVE__'=>__ROOT__.'/Public/Home/Activity',//在服务器上改成""
        '__ADMIN_CSS__'=>__ROOT__.'/Public/Admin/Css',//在服务器上改成""
	    '__ADMIN_IMG__'=>__ROOT__.'/Public/Admin/Images',//在服务器上改成"."
	    '__ADMIN_JS__'=>__ROOT__.'/Public/Admin/Js',//在服务器上改成""
    ),

    /* 前端错误页面模板 */
    'TMPL_ACTION_ERROR'     =>  MODULE_PATH.'View/Public/error.html', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'   =>  MODULE_PATH.'View/Public/success.html', // 默认成功跳转对应的模板文件

    'TOKEN_ON'      =>    true,  // 是否开启令牌验证 默认关闭
    'TOKEN_NAME'    =>    '__hash__',    // 令牌验证的表单隐藏字段名称，默认为__hash__
    'TOKEN_TYPE'    =>    'md5',  //令牌哈希验证规则 默认为MD5
    'TOKEN_RESET'   =>    true,  //令牌验证出错后是否重置令牌 默认为true
);