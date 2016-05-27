<?php

return array(
    
    'ORIGINAL_TABLE_PREFIX' => 'sk_', //默认表前缀

    /* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__ . '/Public/' . GROUP_NAME . '/static',
        '__ADDONS__' => __ROOT__ . '/Public/' . GROUP_NAME . '/Addons',
        '__IMG__'    => __ROOT__ . '/Public/' . GROUP_NAME . '/images',
        '__CSS__'    => __ROOT__ . '/Public/' . GROUP_NAME . '/css',
        '__JS__'     => __ROOT__ . '/Public/' . GROUP_NAME . '/js',
    ),

);
