<?php 
/**
 * 调用外部函数
 * @param unknown $filename
 * @param string $name
 * @return Ambigous <>|Ambigous <unknown>
 */
function model($filename,$name = ''){
    static $_modules = array();
    if (isset($_modules[$name])) {
        return $_modules[$name];
    }
    $model = APP_PATH . "Admin/Common/model/{$filename}/" . strtolower($name) . '.php';
    if (!is_file($model)) {
        die(' Model ' . $name . ' Not Found!');
    }
    require $model;
    $class_name =ucfirst($name);
    $_modules[$name] = new $class_name();
    return $_modules[$name];
}
/**
 * 检测验证码
 * @param  integer $id 验证码ID
 * @return boolean     检测结果
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function check_verify($code, $id = 1){
    $verify = new \Think\Verify();
    return $verify->check($code, $id);
}
/**
 * 三级分类
 * @param unknown $name
 * @param unknown $parents
 * @param unknown $children
 * @param unknown $parentid
 * @param unknown $childid
 * @param unknown $thirdid
 * @return string
 */
function tpl_form_field_category_3level($name, $parents, $children, $parentid, $childid, $thirdid){
    $html = '
	<script type="text/javascript">
		window._' . $name . ' = ' . json_encode($children) . ';
	</script>';
    if (!defined('TPL_INIT_CATEGORY_THIRD')) {
        $html .= '
		<script type="text/javascript">
			function renderCategoryThird(obj, name){
				var index = obj.options[obj.selectedIndex].value;
				require([\'jquery\', \'util\'], function($, u){
					$selectChild = $(\'#\'+name+\'_child\');
		                                                      $selectThird = $(\'#\'+name+\'_third\');
					var html = \'<option value="0">请选择二级分类</option>\';
		                                                      var html1 = \'<option value="0">请选择三级分类</option>\';
					if (!window[\'_\'+name] || !window[\'_\'+name][index]) {
						$selectChild.html(html);
		                                                                        $selectThird.html(html1);
						return false;
					}
					for(var i=0; i< window[\'_\'+name][index].length; i++){
						html += \'<option value="\'+window[\'_\'+name][index][i][\'id\']+\'">\'+window[\'_\'+name][index][i][\'name\']+\'</option>\';
					}
					$selectChild.html(html);
		                                                    $selectThird.html(html1);
				});
			}
		        function renderCategoryThird1(obj, name){
				var index = obj.options[obj.selectedIndex].value;
				require([\'jquery\', \'util\'], function($, u){
					$selectChild = $(\'#\'+name+\'_third\');
					var html = \'<option value="0">请选择三级分类</option>\';
					if (!window[\'_\'+name] || !window[\'_\'+name][index]) {
						$selectChild.html(html);
						return false;
					}
					for(var i=0; i< window[\'_\'+name][index].length; i++){
						html += \'<option value="\'+window[\'_\'+name][index][i][\'id\']+\'">\'+window[\'_\'+name][index][i][\'name\']+\'</option>\';
					}
					$selectChild.html(html);
				});
			}
		</script>
					';
        define('TPL_INIT_CATEGORY_THIRD', true);
    }
    $html .= '<div class="row row-fix tpl-category-container">
		<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
			<select class="form-control tpl-category-parent" id="' . $name . '_parent" name="' . $name . '[parentid]" onchange="renderCategoryThird(this,\'' . $name . '\')">
				<option value="0">请选择一级分类</option>';
    $ops = '';
    foreach ($parents as $row) {
        $html .= '
					<option value="' . $row['id'] . '" ' . ($row['id'] == $parentid ? 'selected="selected"' : '') . '>' . $row['name'] . '</option>';
    }
    $html .= '
			</select>
		</div>
		<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
			<select class="form-control tpl-category-child" id="' . $name . '_child" name="' . $name . '[childid]" onchange="renderCategoryThird1(this,\'' . $name . '\')">
				<option value="0">请选择二级分类</option>';
    if (!empty($parentid) && !empty($children[$parentid])) {
        foreach ($children[$parentid] as $row) {
            $html .= '
						<option value="' . $row['id'] . '"' . ($row['id'] == $childid ? 'selected="selected"' : '') . '>' . $row['name'] . '</option>';
        }
    }
    $html .= '
			</select>
		</div>
	                  <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
			<select class="form-control tpl-category-child" id="' . $name . '_third" name="' . $name . '[thirdid]">
				<option value="0">请选择三级分类</option>';
    if (!empty($childid) && !empty($children[$childid])) {
        foreach ($children[$childid] as $row) {
            $html .= '
						<option value="' . $row['id'] . '"' . ($row['id'] == $thirdid ? 'selected="selected"' : '') . '>' . $row['name'] . '</option>';
        }
    }
    $html .= '</select>
		</div>
	</div>';
    return $html;
}
/**
 * 二级分类
 * @param unknown $name
 * @param unknown $parents
 * @param unknown $children
 * @param unknown $parentid
 * @param unknown $childid
 * @return string
 */
function tpl_form_field_category_2level($name, $parents, $children, $parentid, $childid){
    $html = '
<script type="text/javascript">
	window._'.$name.' = '.json_encode($children).';
</script>';
    if (!defined('TPL_INIT_CATEGORY')) {
        $html .= '
<script type="text/javascript">
	function renderCategory(obj, name){
		var index = obj.options[obj.selectedIndex].value;
		require([\'jquery\', \'util\'], function($, u){
			$selectChild = $(\'#\'+name+\'_child\');
			var html = \'<option value="0">请选择二级分类</option>\';
			if (!window[\'_\'+name] || !window[\'_\'+name][index]) {
				$selectChild.html(html);
				return false;
			}
			for(var i=0; i< window[\'_\'+name][index].length; i++){
				html += \'<option value="\'+window[\'_\'+name][index][i][\'id\']+\'">\'+window[\'_\'+name][index][i][\'name\']+\'</option>\';
			}
			$selectChild.html(html);
		});
	}
</script>
			';
        define('TPL_INIT_CATEGORY', true);
    }

    $html .=
    '<div class="row row-fix tpl-category-container">
	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
		<select class="form-control tpl-category-parent" id="'.$name.'_parent" name="'.$name.'[parentid]" onchange="renderCategory(this,\''.$name.'\')">
			<option value="0">请选择一级分类</option>';
    $ops = '';
    foreach ($parents as $row) {
        $html .= '
			<option value="'.$row['id'].'" '.(($row['id'] == $parentid) ? 'selected="selected"' : '').'>'.$row['name'].'</option>';
    }
    $html .='
		</select>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
		<select class="form-control tpl-category-child" id="'.$name.'_child" name="'.$name.'[childid]">
			<option value="0">请选择二级分类</option>';
    if (!empty($parentid) && !empty($children[$parentid])){
        foreach ($children[$parentid] as $row) {
            $html .= '
			<option value="'.$row['id'].'"'.(($row['id'] == $childid)? 'selected="selected"':'').'>'.$row['name'].'</option>';
        }
    }
    $html .='
		</select>
	</div>
</div>
';
    return $html;
}
/**
 * 地区三级联动
 * @param unknown $name
 * @param unknown $values
 * @return string
 */
function tpl_form_field_district($name, $values = array()) {
    $html = '';
    if (!defined('TPL_INIT_DISTRICT')) {
        $html .= '
		<script type="text/javascript">
			require(["jquery", "district"], function($, dis){
				$(".tpl-district-container").each(function(){
					var elms = {};
					elms.province = $(this).find(".tpl-province")[0];
					elms.city = $(this).find(".tpl-city")[0];
					elms.district = $(this).find(".tpl-district")[0];
					var vals = {};
					vals.province = $(elms.province).attr("data-value");
					vals.city = $(elms.city).attr("data-value");
					vals.district = $(elms.district).attr("data-value");
					dis.render(elms, vals, {withTitle: true});
				});
			});
		</script>';
        define('TPL_INIT_DISTRICT', true);
    }
    if (empty($values) || !is_array($values)) {
        $values = array('province'=>'','city'=>'','district'=>'');
    }
    if(empty($values['province'])) {
        $values['province'] = '';
    }
    if(empty($values['city'])) {
        $values['city'] = '';
    }
    if(empty($values['district'])) {
        $values['district'] = '';
    }
    $html .= '
		<div class="row row-fix tpl-district-container">
			<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
				<select name="' . $name . '[province]" data-value="' . $values['province'] . '" class="form-control tpl-province">
				</select>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
				<select name="' . $name . '[city]" data-value="' . $values['city'] . '" class="form-control tpl-city">
				</select>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
				<select name="' . $name . '[district]" data-value="' . $values['district'] . '" class="form-control tpl-district">
				</select>
			</div>
		</div>';
    return $html;
}
/**
 * 获取日期区间
 * @param unknown $name
 * @param unknown $value
 * @param string $time
 * @return string
 */
function tpl_form_field_daterange($name, $value = array(), $time = false) {
    $s = '';

    if (empty($time) && !defined('TPL_INIT_DATERANGE_DATE')) {
        $s = '
<script type="text/javascript">
	require(["daterangepicker"], function($){
		$(function(){
			$(".daterange.daterange-date").each(function(){
				var elm = this;
				$(this).daterangepicker({
					startDate: $(elm).prev().prev().val(),
					endDate: $(elm).prev().val(),
					format: "YYYY-MM-DD",
				}, function(start, end){
					$(elm).find(".date-title").html(start.toDateStr() + " 至 " + end.toDateStr());
					$(elm).prev().prev().val(start.toDateStr());
					$(elm).prev().val(end.toDateStr());
				});
			});
		});
	});
</script>
';
        define('TPL_INIT_DATERANGE_DATE', true);
    }

    if (!empty($time) && !defined('TPL_INIT_DATERANGE_TIME')) {
        $s = '
<script type="text/javascript">
	require(["daterangepicker"], function($){
		$(function(){
			$(".daterange.daterange-time").each(function(){
				var elm = this;
				$(this).daterangepicker({
					startDate: $(elm).prev().prev().val(),
					endDate: $(elm).prev().val(),
					format: "YYYY-MM-DD HH:mm",
					timePicker: true,
					timePicker12Hour : false,
					timePickerIncrement: 1,
					minuteStep: 1
				}, function(start, end){
					$(elm).find(".date-title").html(start.toDateTimeStr() + " 至 " + end.toDateTimeStr());
					$(elm).prev().prev().val(start.toDateTimeStr());
					$(elm).prev().val(end.toDateTimeStr());
				});
			});
		});
	});
</script>
';
        define('TPL_INIT_DATERANGE_TIME', true);
    }

    if($value['start']) {
        $value['starttime'] = empty($time) ? date('Y-m-d',strtotime($value['start'])) : date('Y-m-d H:i',strtotime($value['start']));
    }
    if($value['end']) {
        $value['endtime'] = empty($time) ? date('Y-m-d',strtotime($value['end'])) : date('Y-m-d H:i',strtotime($value['end']));
    }
    $value['starttime'] = empty($value['starttime']) ? (empty($time) ? date('Y-m-d') : date('Y-m-d H:i') ): $value['starttime'];
    $value['endtime'] = empty($value['endtime']) ? $value['starttime'] : $value['endtime'];
    $s .= '
	<input name="'.$name . '[start]'.'" type="hidden" value="'. $value['starttime'].'" />
	<input name="'.$name . '[end]'.'" type="hidden" value="'. $value['endtime'].'" />
	<button class="btn btn-default daterange '.(!empty($time) ? 'daterange-time' : 'daterange-date').'" type="button"><span class="date-title">'.$value['starttime'].' 至 '.$value['endtime'].'</span> <i class="fa fa-calendar"></i></button>
	';
    return $s;
}
/**
 * 日期
 * @param unknown $name
 * @param string $value
 * @param string $withtime
 * @return string
 */
function tpl_form_field_date($name, $value = '', $withtime = false) {
    $s = '';
    if (!defined('TPL_INIT_DATA')) {
        $s = '
			<script type="text/javascript">
            	require(["datetimepicker"], function($){
            		$(function(){
            			$(".datetimepicker").each(function(){
            				var opt = {
            					language: "zh-CN",
            					minView: 0,
            					autoclose: true,
            					format : "yyyy-mm-dd hh:ii",
            					todayBtn: true,
            					minuteStep: 5
            				};
            				$(this).datetimepicker(opt);
            			});
            		});
            	});
        </script>';
        define('TPL_INIT_DATA', true);
    }
    $withtime = empty($withtime) ? false : true;
    if (!empty($value)) {
        $value = strexists($value, '-') ? strtotime($value) : $value;
    } else {
        $value = TIMESTAMP;
    }
    $value = ($withtime ? date('Y-m-d H:i:s', $value) : date('Y-m-d', $value));
    $s .= '<input type="text" name="' . $name . '"  value="'.$value.'" placeholder="请选择日期时间" readonly="readonly" class="datetimepicker form-control" style="padding-left:12px;" />';
    return $s;
}
/**
 * 浏览地图
 * @param unknown $field
 * @param unknown $value
 * @return string
 */
function tpl_form_field_coordinate($field, $value = array()) {
    $s = '';
    if(!defined('TPL_INIT_COORDINATE')) {
        $s .= '<script type="text/javascript">
				function showCoordinate(elm) {
					require(["util"], function(util){
						var val = {};
						val.lng = parseFloat($(elm).parent().prev().prev().find(":text").val());
						val.lat = parseFloat($(elm).parent().prev().find(":text").val());
						util.map(val, function(r){
							$(elm).parent().prev().prev().find(":text").val(r.lng);
							$(elm).parent().prev().find(":text").val(r.lat);
						});

					});
				}

			</script>';
        define('TPL_INIT_COORDINATE', true);
    }
    $s .= '
		<div class="row row-fix">
			<div class="col-xs-4 col-sm-4">
				<input type="text" name="' . $field . '[lng]" value="'.$value['lng'].'" placeholder="地理经度"  class="form-control" />
			</div>
			<div class="col-xs-4 col-sm-4">
				<input type="text" name="' . $field . '[lat]" value="'.$value['lat'].'" placeholder="地理纬度"  class="form-control" />
			</div>
			<div class="col-xs-4 col-sm-4">
				<button onclick="showCoordinate(this);" class="btn btn-default" type="button">选择坐标</button>
			</div>
		</div>';
    return $s;
}
/**
 * 获取图标
 * @param unknown $name 图标名称
 * @param string $value 图标值
 * @return string
 */
function tpl_form_field_icon($name, $value='',$type=0) {
	if(empty($value)){
		if(empty($type)){
			$value = 'fa fa-external-link';
		}
	}
	$s = '';
	if (!defined('TPL_INIT_ICON')) {
		$s = '
		<script type="text/javascript">
			function showIconDialog(elm) {
				require(["util","jquery"], function(u, $){
					var btn = $(elm);
					var spview = btn.parent().prev();
					var ipt = spview.prev();
					if(!ipt.val()){
						spview.css("display","none");
					}
					u.iconBrowser(function(ico){
						ipt.val(ico);
						spview.show();
						spview.find("i").attr("class","");
						spview.find("i").addClass("fa").addClass(ico);
					});
				});
			}
		</script>';
		define('TPL_INIT_ICON', true);
	}
	$s .= '
	<div class="input-group" style="width: 300px;">
		<input type="text" value="'.$value.'" name="'.$name.'" class="form-control" autocomplete="off">
		<span class="input-group-addon"><i class="'.$value.' fa"></i></span>
		<span class="input-group-btn">
			<button class="btn btn-default" type="button" onclick="showIconDialog(this);">选择图标</button>
		</span>
	</div>
	';
	return $s;
}
/**
 * 获取颜色
 * @param unknown $name 颜色名称
 * @param string $value 颜色值
 * @return string
 */
function tpl_form_field_color($name, $value = '') {
    $s = '';
    if (!defined('TPL_INIT_COLOR')) {
        $s = '
		<script type="text/javascript">
			require(["jquery", "util"], function($, util){
				$(function(){
					$(".colorpicker").each(function(){
						var elm = this;
						util.colorpicker(elm, function(color){
							$(elm).parent().prev().find(":text").val(color.toHexString());
						});
					});
					$(".colorclean").click(function(){
						$(this).parent().prev().val("");
						var $container = $(this).parent().parent().parent().next();
						$container.find(".colorpicker").val("");
						$container.find(".sp-preview-inner").css("background-color","#000000");
					});
				});
			});
		</script>';
        define('TPL_INIT_COLOR', true);
    }
    $s .= '
		<div class="row row-fix">
			<div class="col-xs-6 col-sm-4" style="padding-right:0;">
				<div class="input-group">
					<input class="form-control" type="text" placeholder="请选择颜色" value="'.$value.'">
					<span class="input-group-btn">
						<button class="btn btn-default colorclean" type="button">
							<span><i class="fa fa-remove"></i></span>
						</button>
					</span>
				</div>
			</div>
			<div class="col-xs-2" style="padding:2px 0;">
				<input class="colorpicker" type="hidden" name="'.$name.'" value="'.$value.'" placeholder="">
			</div>
		</div>
		';
    return $s;
}
/**
 * 单图上传
 * @param unknown $name 名称
 * @param string $value
 * @param string $default
 * @param unknown $options
 * @return string
 */
function tpl_form_field_image($name, $value = '', $default = '', $options = array()) {
    global $_W;

    if(empty($default)) {
        $default = './Public/Admin/Images/nopic.jpg';
    }
    $val = $default;
    if(!empty($value)) {
        $val = tomedia($value);
    }
    if(empty($options['tabs'])){
        $options['tabs'] = array('upload'=>'active', 'browser'=>'', 'crawler'=>'');
    }
    if(!empty($options['global'])){
        $options['global'] = true;
    } else {
        $options['global'] = false;
    }
    if(empty($options['class_extra'])) {
        $options['class_extra'] = '';
    }
    if (isset($options['dest_dir']) && !empty($options['dest_dir'])) {
        if (!preg_match('/^\w+([\/]\w+)?$/i', $options['dest_dir'])) {
            exit('图片上传目录错误,只能指定最多两级目录,如: "we7_store","we7_store/d1"');
        }
    }

    $options['direct'] = true;
    $options['multi'] = false;

    if(isset($options['thumb'])){
        $options['thumb'] = !empty($options['thumb']);
    }

    $s = '';
    if (!defined('TPL_INIT_IMAGE')) {
        $s = '
		<script type="text/javascript">
			function showImageDialog(elm, opts, options) {
				require(["util"], function(util){
					var btn = $(elm);
					var ipt = btn.parent().prev();
					var val = ipt.val();
					var img = ipt.parent().next().children();

					util.image(val, function(url){
						if(url.url){
							if(img.length > 0){
								img.get(0).src = url.url;
							}
							ipt.val(url.filename);
							ipt.attr("filename",url.filename);
							ipt.attr("url",url.url);
						}
						if(url.media_id){
							if(img.length > 0){
								img.get(0).src = "";
							}
							ipt.val(url.media_id);
						}
					}, opts, options);
				});
			}
			function deleteImage(elm){
				require(["jquery"], function($){
                    $(elm).prev().attr("src", "./Public/Admin/Images/nopic.jpg");
					$(elm).parent().prev().find("input").val("");
				});
			}
		</script>';
        define('TPL_INIT_IMAGE', true);
    }

    $s .= '
<div class="input-group '. $options['class_extra'] .'">
	<input type="text" name="'.$name.'" value="'.$value.'"'.($options['extras']['text'] ? $options['extras']['text'] : '').' class="form-control" autocomplete="off">
	<span class="input-group-btn">
		<button class="btn btn-default" type="button" onclick="showImageDialog(this, \'' . base64_encode(iserializer($options)) . '\', '. str_replace('"','\'', json_encode($options)).');">选择图片</button>
	</span>
</div>';
    if(!empty($options['tabs']['browser']) || !empty($options['tabs']['upload'])){
        $s .=
        '<div class="input-group '. $options['class_extra'] .'" style="margin-top:.5em;">
				<img src="' . $val . '" onerror="this.src=\''.$default.'\'; this.title=\'图片未找到.\'" class="img-responsive img-thumbnail" '.($options['extras']['image'] ? $options['extras']['image'] : '').' width="150" />
				<em class="close" style="position:absolute; top: 0px; right: -14px;" title="删除这张图片" onclick="deleteImage(this)">×</em>
			</div>';
    }
    return $s;
}
/**
 * 多图上传
 * @param unknown $name
 * @param unknown $value
 * @param unknown $options
 * @return string
 */
function tpl_form_field_multi_image($name, $value = array(), $options = array()) {
	global $_W;
	
	if(empty($options['tabs'])){
		$options['tabs'] = array('upload'=>'active', 'browser'=>'', 'crawler'=>'');
	}
	$options['multi'] = true;
	$options['direct'] = false;
	
	$s = '';
	if (!defined('TPL_INIT_MULTI_IMAGE')) {
		$s = '
<script type="text/javascript">
	function uploadMultiImage(elm) {
		require(["jquery","util"], function($, util){
			var name = $(elm).next().val();
			util.image( "", function(urls){
				$.each(urls, function(idx, url){
					$(elm).parent().parent().next().append(\'<div class="multi-item"><img onerror="this.src=\\\'./resource/images/nopic.jpg\\\'; this.title=\\\'图片未找到.\\\'" src="\'+url.url+\'" class="img-responsive img-thumbnail"><input type="hidden" name="\'+name+\'[]" value="\'+url.filename+\'"><em class="close" title="删除这张图片" onclick="deleteMultiImage(this)">×</em></div>\');
				});
			}, "", '.json_encode($options).');
		});
	}
	function deleteMultiImage(elm){
		require(["jquery"], function($){
			$(elm).parent().remove();
		});
	}
</script>';
		define('TPL_INIT_MULTI_IMAGE', true);
	}

	$s .= <<<EOF
<div class="input-group">
	<input type="text" class="form-control" readonly="readonly" value="" placeholder="批量上传图片" autocomplete="off">
	<span class="input-group-btn">
		<button class="btn btn-default" type="button" onclick="uploadMultiImage(this);">选择图片</button>
		<input type="hidden" value="{$name}" />
	</span>
</div>
<div class="input-group multi-img-details">
EOF;
	if (is_array($value) && count($value)>0) {
		foreach ($value as $row) {
			$s .='
<div class="multi-item">
	<img src="'.tomedia($row).'" onerror="this.src=\'./resource/images/nopic.jpg\'; this.title=\'图片未找到.\'" class="img-responsive img-thumbnail">
	<input type="hidden" name="'.$name.'[]" value="'.$row.'" >
	<em class="close" title="删除这张图片" onclick="deleteMultiImage(this)">×</em>
</div>';
		}
	}
	$s .= '</div>';

	return $s;
}
/**
 * @param string $id 名称
 * @param string $value 填充值
 * @author 赵鑫 <786824455@qq.com>
 */
function tpl_ueditor($id, $value = '') {
    $s = '';
    if (!defined('TPL_INIT_UEDITOR')) {
        $s .= ' <script type="text/javascript" charset="utf-8" src="./Public/Admin/Js/components/ueditor/ueditor.config.js"></script>
                <script type="text/javascript" charset="utf-8" src="./Public/Admin/Js/components/ueditor/ueditor.all.js"> </script>
                <script type="text/javascript" charset="utf-8" src="./Public/Admin/Js/components/ueditor/lang/zh-cn/zh-cn.js"></script> ';
        
    }
    $s .= !empty($id) ? "<textarea id=\"{$id}\" name=\"{$id}\" type=\"text/plain\" style=\"height:300px;\">{$value}</textarea>" : '';
    $s .= "
	<script type=\"text/javascript\">
			var ueditoroption = {
				'autoClearinitialContent' : false,
				'toolbars' : [['fullscreen', 'source', 'preview', '|', 'bold', 'italic', 'underline', 'strikethrough', 'forecolor', 'backcolor', '|',
					'justifyleft', 'justifycenter', 'justifyright', '|', 'insertorderedlist', 'insertunorderedlist', 'blockquote', 'emotion', 'insertvideo',
					'link', 'removeformat', '|', 'rowspacingtop', 'rowspacingbottom', 'lineheight','indent', 'paragraph', 'fontsize', '|',
					'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol',
					'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', '|', 'anchor', 'map', 'print', 'drafts']],
				'elementPathEnabled' : false,
				'initialFrameHeight': 500,
				'focus' : false,
				'maximumWords' : 9999999999999
			};
			var opts = {
				type :'image',
				direct : false,
				multi : true,
				tabs : {
					'upload' : 'active',
					'browser' : '',
					'crawler' : ''
				},
				path : '',
				dest_dir : '',
				global : false,
				thumb : false,
				width : 0
			};
			UE.registerUI('myinsertimage',function(editor,uiName){
				editor.registerCommand(uiName, {
					execCommand:function(){
						require(['fileUploader'], function(uploader){
							uploader.show(function(imgs){
								if (imgs.length == 0) {
									return;
								} else if (imgs.length == 1) {
									editor.execCommand('insertimage', {
										'src' : imgs[0]['url'],
										'_src' : imgs[0]['attachment'],
										'width' : '100%',
										'alt' : imgs[0].filename
									});
								} else {
									var imglist = [];
									for (i in imgs) {
										imglist.push({
											'src' : imgs[i]['url'],
											'_src' : imgs[i]['attachment'],
											'width' : '100%',
											'alt' : imgs[i].filename
										});
									}
									editor.execCommand('insertimage', imglist);
								}
							}, opts);
						});
					}
				});
				var btn = new UE.ui.Button({
					name: '插入图片',
					title: '插入图片',
					cssRules :'background-position: -726px -77px',
					onclick:function () {
						editor.execCommand(uiName);
					}
				});
				editor.addListener('selectionchange', function () {
					var state = editor.queryCommandState(uiName);
					if (state == -1) {
						btn.setDisabled(true);
						btn.setChecked(false);
					} else {
						btn.setDisabled(false);
						btn.setChecked(state);
					}
				});
				return btn;
			}, 19);
			".(!empty($id) ? "
    			    $(function(){
    			    var ue = UE.getEditor('{$id}');
    			    $('#{$id}').data('editor', ue);
    			    $('#{$id}').parents('form').submit(function() {
    			    if (ue.queryCommandState('source')) {
    							ue.execCommand('source');
    }
    });
    });" : '')."
    </script>";
    return $s;
}

// 获取数据的状态操作
function show_status_op($status) {
    switch ($status){
        case 0  : return    '启用';     break;
        case 1  : return    '禁用';     break;
        case 2  : return    '审核';		break;
        default : return    false;      break;
    }
}
/**
 * 获取配置的分组
 * @param string $group 配置分组
 * @return string
 */
function get_config_group($group=0){
    $list = C('CONFIG_GROUP_LIST');
    return $group?$list[$group]:'';
}
/**
 * 获取配置的类型
 * @param string $type 配置类型
 * @return string
 */
function get_config_type($type=0){
    $list = C('CONFIG_TYPE_LIST');
    return $list[$type];
}
 // 分析枚举类型配置值 格式 a:名称1,b:名称2
function parse_config_attr($string) {
    $array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));
    if(strpos($string,':')){
        $value  =   array();
        foreach ($array as $val) {
            list($k, $v) = explode(':', $val);
            $value[$k]   = $v;
        }
    }else{
        $value  =   $array;
    }
    return $value;
}
/**
 * 根据表id获取数据库中任何表中的某个字段值
 * @param  integer $uid 用户ID
 * @param  string $model 模型名称
 * @param  string $field 模型下的字段名称
 * @param  where  使用该方法的条件必须是表中有id字段
 * @return string 文章管理的文章类型名称
 * @author 撒哈拉的寂寞 <1032453491@qq.com>
 */
function getNameTableid($id = 0,$model='User',$field='username'){
    static $list;
	if(empty($id)){
		return false;
	}
    if(empty($list)){
        $list = S('sys_'.$model.'_'.base64_encode(json_encode($field)).'_list');
    }
    $key = "art_{$id}";
    if(isset($list[$key])){ //已缓存，直接使用
        $name = $list[$key];
    } else { //调用接口获取用户信息
       $info = M($model)->where(array('id'=>$id))->getField($field,'|');
       if($info !== false && !empty($info) ){
       		if(is_array($info)){
	            $name = $list[$key] =$info[$id];
	            	//缓存用户 
	            $count = count($list);
	            $max   = C('USER_MAX_CACHE');
	            while ($count-- > $max) {
	                array_shift($list);
	            }
	            S('sys_'.$model.'_'.base64_encode(json_encode($field)).'_list', $list);
		   	}elseif(is_string($info)){
	            $name = $list[$key] = $info;
	            	//缓存用户 
	            $count = count($list);
	            $max   = C('USER_MAX_CACHE');
	            while ($count-- > $max) {
	                array_shift($list);
	            }
	            S('sys_'.$model.'_'.base64_encode(json_encode($field)).'_list', $list);
		   	}
        } else {
            $name = '';
        }
    }
    return $name;
}

/**
 * 根据用户ID获取用户昵称
 * @param  integer $uid 用户ID
 * @return string       用户昵称
 */
function get_member_groups($uid = 0){
    static $list;
    if(empty($list)){
        $list = S('sys_member_groups_list');
    }
    $key = "mg_{$uid}";
    if(isset($list[$key])){ //已缓存，直接使用
        $name = $list[$key];
    } else { //调用接口获取用户信息
        $info = M('MemberGroups')->field('title')->find($uid);
        if($info !== false && $info['nickname'] ){
            $nickname = $info['nickname'];
            $name = $list[$key] = $nickname;
            /* 缓存用户 */
            $count = count($list);
            $max   = C('USER_MAX_CACHE');
            while ($count-- > $max) {
                array_shift($list);
            }
            S('sys_member_groups_list', $list);
        } else {
            $name = '';
        }
    }
    return $name;
}
?>
