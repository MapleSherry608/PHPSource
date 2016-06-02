/**
 * Home require 配置
 */
require.config({
	baseUrl: 'Public/Home/Js/app',
	paths: {
		'jquery': '../lib/jquery-1.11.1.min',
		'jquery.ui': '../lib/jquery-ui-1.10.3.min',
		'jquery.gcjs': '../lib/jquery.gcjs',
		'bootstrap': '../lib/bootstrap.min',
		'tpl':'../lib/tmodjs',
		'jquery.touchslider':'../lib/jquery.touchslider.min',
        'swipe':'../lib/swipe',
        'sweetalert':'../lib/sweetalert/sweetalert.min'
	},
	shim:{
		'jquery.ui': {
			exports: "$",
			deps: ['jquery']
		},
		'jquery.gcjs': {
			exports: "$",
			deps: ['jquery']
		},
		'bootstrap': {
			exports: "$",
			deps: ['jquery']
		},
		'jquery.touchslider': {
            exports: "$",
            deps: ['jquery']
        },
        'sweetalert':{
            exports: "$",
            deps: ['css!../lib/sweetalert/sweetalert.css']
        }
	}
});