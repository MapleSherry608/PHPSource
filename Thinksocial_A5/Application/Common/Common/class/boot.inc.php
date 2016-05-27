<?php
global $_W, $_GPC;
$_W = $_SESSION;
$_W['attachurl'] = getSiteRoot();
$_W['uniacid'] = 0;
$_GPC = I();
function array_elements($keys, $src, $default = FALSE) {
    $return = array();
    if(!is_array($keys)) {
        $keys = array($keys);
    }
    foreach($keys as $key) {
        if(isset($src[$key])) {
            $return[$key] = $src[$key];
        } else {
            $return[$key] = $default;
        }
    }
    return $return;
}

function file_write($filename, $data) {
    $filename = ATTACHMENT_ROOT . '/' . $filename;
    mkdirs(dirname($filename));
    file_put_contents($filename, $data);
    @chmod($filename, 420);
    return is_file($filename);
}

function file_move($filename, $dest) {
    mkdirs(dirname($dest));
    if (is_uploaded_file($filename)) {
        move_uploaded_file($filename, $dest);
    } else {
        rename($filename, $dest);
    }
    @chmod($filename, 420);
    return is_file($dest);
}

function file_tree($path) {
    $files = array();
    $ds = glob($path . '/*');
    if (is_array($ds)) {
        foreach ($ds as $entry) {
            if (is_file($entry)) {
                $files[] = $entry;
            }
            if (is_dir($entry)) {
                $rs = file_tree($entry);
                foreach ($rs as $f) {
                    $files[] = $f;
                }
            }
        }
    }
    return $files;
}
function file_copy($src, $des, $filter) {
    $dir = opendir($src);
    @mkdir($des);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                file_copy($src . '/' . $file, $des . '/' . $file, $filter);
            } elseif (!in_array(substr($file, strrpos($file, '.') + 1), $filter)) {
                copy($src . '/' . $file, $des . '/' . $file);
            }
        }
    }
    closedir($dir);
}
function rmdirs($path, $clean = false) {
    if (!is_dir($path)) {
        return false;
    }
    $files = glob($path . '/*');
    if ($files) {
        foreach ($files as $file) {
            is_dir($file) ? rmdirs($file) : @unlink($file);
        }
    }
    return $clean ? true : @rmdir($path);
}
function file_image_thumb($srcfile, $desfile = '', $width = 0) {
    if (!file_exists($srcfile)) {
        return error('-1', '原图像不存在');
    }
    if (intval($width) < 0) {
        return error('-1', '缩放宽度无效');
    }
    if (empty($desfile)) {
        $ext = pathinfo($srcfile, PATHINFO_EXTENSION);
        $srcdir = dirname($srcfile);
        do {
            $desfile = $srcdir . '/' . random(30) . ".{$ext}";
        } while (file_exists($desfile));
    }

    $des = dirname($desfile);
    if (!file_exists($des)) {
        if (!mkdirs($des)) {
            return error('-1', '创建目录失败');
        }
    } elseif (!is_writable($des)) {
        return error('-1', '目录无法写入');
    }

    $org_info = @getimagesize($srcfile);
    if ($org_info) {
        if ($width == 0 || $width > $org_info[0]) {
            copy($srcfile, $desfile);
            return str_replace(ATTACHMENT_ROOT . '/', '', $desfile);
        }
        if ($org_info[2] == 1) { 			if (function_exists("imagecreatefromgif")) {
            $img_org = imagecreatefromgif($srcfile);
        }
        } elseif ($org_info[2] == 2) {
            if (function_exists("imagecreatefromjpeg")) {
                $img_org = imagecreatefromjpeg($srcfile);
            }
        } elseif ($org_info[2] == 3) {
            if (function_exists("imagecreatefrompng")) {
                $img_org = imagecreatefrompng($srcfile);
                imagesavealpha($img_org, true);
            }
        }
    } else {
        return error('-1', '获取原始图像信息失败');
    }
    $scale_org = $org_info[0] / $org_info[1];
    $height = $width / $scale_org;
    if (function_exists("imagecreatetruecolor") && function_exists("imagecopyresampled") && @$img_dst = imagecreatetruecolor($width, $height)) {
        imagealphablending($img_dst, false);
        imagesavealpha($img_dst, true);
        imagecopyresampled($img_dst, $img_org, 0, 0, 0, 0, $width, $height, $org_info[0], $org_info[1]);
    } else {
        return error('-1', 'PHP环境不支持图片处理');
    }
    if ($org_info[2] == 2) {
        if (function_exists('imagejpeg')) {
            imagejpeg($img_dst, $desfile);
        }
    } else {
        if (function_exists('imagepng')) {
            imagepng($img_dst, $desfile);
        }
    }

    imagedestroy($img_dst);
    imagedestroy($img_org);

    return str_replace(ATTACHMENT_ROOT . '/', '', $desfile);
}
function file_upload($file, $type = 'image', $name = '', $is_wechat = false) {
    $setting = array(
        'limit'=>1000,
        'folder'=>'/'.date('Y/m/'),//前缀
        'width'=>'',
        'upload' => array(
            'image'=>array(
                'extentions'=>array(
                    '0'=>gif,
                    '1'=>jpg,
                    '2'=>jpeg,
                    '3'=>png,
                ),
                'limit'=>5000
            ),
            'attachdir'=>'attachment',
            'audio'=>array(
                'extentions'=>array(
                    '0'=> 'mp3'
                ),
                'limit'=>5000
            ),
        ),
    );
    $harmtype = array('asp', 'php', 'jsp', 'js', 'css', 'php3', 'php4', 'php5', 'ashx', 'aspx', 'exe', 'cgi');
    if (empty($file)) {
        return error(-1, '没有上传内容');
    }
    if (!in_array($type, array('image', 'thumb', 'voice', 'video', 'audio'))) {
        return error(-2, '未知的上传类型');
    }
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $ext = strtolower($ext);
    if (!$is_wechat) {
		$setting = $setting['upload'][$type];
		if (!in_array(strtolower($ext), $setting['extentions']) || in_array(strtolower($ext), $harmtype)) {
			return error(-3, '不允许上传此类文件');
		}
		if (!empty($setting['limit']) && $setting['limit'] * 1024 < filesize($file['tmp_name'])) {
			return error(-4, "上传的文件超过大小限制，请上传小于 {$setting['limit']}k 的文件");
		}
	}
    $result = array();
    if (empty($name) || $name == 'auto') {
        $path = "{$type}s/" . date('Y/m/');
        mkdirs(ATTACHMENT_ROOT . '/' . $path);
        $filename = file_random_name(ATTACHMENT_ROOT . '/' . $path, $ext);

        $result['path'] = $path . $filename;
    } else {
        mkdirs(dirname(ATTACHMENT_ROOT . '/' . $name));
        if (!strexists($name, $ext)) {
            $name .= '.' . $ext;
        }
        $result['path'] = $name;
    }
    if (!file_move($file['tmp_name'], ATTACHMENT_ROOT . '/' . $result['path'])) {
        return error(-1, '保存上传文件失败');
    }

    $result['success'] = true;

    return $result;
}

function file_random_name($dir, $ext){
    do {
        $filename = random(30) . '.' . $ext;
    } while (file_exists($dir . $filename));

    return $filename;
}
function sizecount($size) {
    if($size >= 1073741824) {
        $size = round($size / 1073741824 * 100) / 100 . ' GB';
    } elseif($size >= 1048576) {
        $size = round($size / 1048576 * 100) / 100 . ' MB';
    } elseif($size >= 1024) {
        $size = round($size / 1024 * 100) / 100 . ' KB';
    } else {
        $size = $size . ' Bytes';
    }
    return $size;
}

function file_delete($file) {
    if (empty($file)) {
        return FALSE;
    }
    if (file_exists(ATTACHMENT_ROOT . '/' . $file)) {
        @unlink(ATTACHMENT_ROOT . '/' . $file);
    }
    return TRUE;
}

function file_image_crop($src, $desfile, $width = 400, $height = 300, $position = 1) {
    if (!file_exists($src)) {
        return error('-1', '原图像不存在');
    }
    if (intval($width) <= 0 || intval($height) <= 0) {
        return error('-1', '裁剪尺寸无效');
    }
    if (intval($position) > 9 || intval($position) < 1) {
        return error('-1', '裁剪位置无效');
    }

    $des = dirname($desfile);
    if (!file_exists($des)) {
        if (!mkdirs($des)) {
            return error('-1', '创建目录失败');
        }
    } elseif (!is_writable($des)) {
        return error('-1', '目录无法写入');
    }
    $org_info = @getimagesize($src);
    if ($org_info) {
        if ($org_info[2] == 1) { 			if (function_exists("imagecreatefromgif")) {
            $img_org = imagecreatefromgif($src);
        }
        } elseif ($org_info[2] == 2) {
            if (function_exists("imagecreatefromjpeg")) {
                $img_org = imagecreatefromjpeg($src);
            }
        } elseif ($org_info[2] == 3) {
            if (function_exists("imagecreatefrompng")) {
                $img_org = imagecreatefrompng($src);
            }
        }
    } else {
        return error('-1', '获取原始图像信息失败');
    }

    if ($width == '0' || $width > $org_info[0]) {
        $width = $org_info[0];
    }
    if ($height == '0' || $height > $org_info[1]) {
        $height = $org_info[1];
    }
    switch ($position) {
        case 0 :
        case 1 :
            $dst_x = 0;
            $dst_y = 0;
            break;
        case 2 :
            $dst_x = ($org_info[0] - $width) / 2;
            $dst_y = 0;
            break;
        case 3 :
            $dst_x = $org_info[0] - $width;
            $dst_y = 0;
            break;
        case 4 :
            $dst_x = 0;
            $dst_y = ($org_info[1] - $height) / 2;
            break;
        case 5 :
            $dst_x = ($org_info[0] - $width) / 2;
            $dst_y = ($org_info[1] - $height) / 2;
            break;
        case 6 :
            $dst_x = $org_info[0] - $width;
            $dst_y = ($org_info[1] - $height) / 2;
            break;
        case 7 :
            $dst_x = 0;
            $dst_y = $org_info[1] - $height;
            break;
        case 8 :
            $dst_x = ($org_info[0] - $width) / 2;
            $dst_y = $org_info[1] - $height;
            break;
        case 9 :
            $dst_x = $org_info[0] - $width;
            $dst_y = $org_info[1] - $height;
            break;
        default:
            $dst_x = 0;
            $dst_y = 0;
    }
    if ($width == $org_info[0]) {
        $dst_x = 0;
    }
    if ($height == $org_info[1]) {
        $dst_y = 0;
    }

    if (function_exists("imagecreatetruecolor") && function_exists("imagecopyresampled") && @$img_dst = imagecreatetruecolor($width, $height)) {
        imagecopyresampled($img_dst, $img_org, 0, 0, $dst_x, $dst_y, $width, $height, $width, $height);
    } else {
        return error('-1', 'PHP环境不支持图片处理');
    }
    if (function_exists('imagejpeg')) {
        imagejpeg($img_dst, $desfile);
    } elseif (function_exists('imagepng')) {
        imagepng($img_dst, $desfile);
    }
    imagedestroy($img_dst);
    imagedestroy($img_org);
    return true;
}


function file_lists($filepath, $subdir = 1, $ex = '', $isdir = 0, $md5 = 0, $enforcement = 0) {
    static $file_list = array();
    if ($enforcement) $file_list = array();
    $flags = $isdir ? GLOB_ONLYDIR : 0;
    $list = glob($filepath . '*' . (!empty($ex) && empty($subdir) ? '.' . $ex : ''), $flags);
    if (!empty($ex)) $ex_num = strlen($ex);
    foreach ($list as $k => $v) {
        $v = str_replace('\\', '/', $v);
        $v1 = str_replace(IA_ROOT . '/', '', $v);
        if ($subdir && is_dir($v)) {
            file_lists($v . '/', $subdir, $ex, $isdir, $md5);
            continue;
        }
        if (!empty($ex) && strtolower(substr($v, -$ex_num, $ex_num)) == $ex) {

            if ($md5) {
                $file_list[$v1] = md5_file($v);
            } else {
                $file_list[] = $v1;
            }
            continue;
        } elseif (!empty($ex) && strtolower(substr($v, -$ex_num, $ex_num)) != $ex) {
            unset($list[$k]);
            continue;
        }
    }
    return $file_list;
}





function ihttp_get($url) {
    return ihttp_request($url);
}

function ihttp_post($url, $data) {
    $headers = array('Content-Type' => 'application/x-www-form-urlencoded');
    return ihttp_request($url, $data, $headers);
}

function ihttp_request($url, $post = '', $extra = array(), $timeout = 60) {
    $urlset = parse_url($url);
    if (empty($urlset['path'])) {
        $urlset['path'] = '/';
    }
    if (!empty($urlset['query'])) {
        $urlset['query'] = "?{$urlset['query']}";
    }
    if (empty($urlset['port'])) {
        $urlset['port'] = $urlset['scheme'] == 'https' ? '443' : '80';
    }
    if (strexists($url, 'https://') && !extension_loaded('openssl')) {
        if (!extension_loaded("openssl")) {
            $this->error('请开启您PHP环境的openssl');
        }
    }
    if (function_exists('curl_init') && function_exists('curl_exec')) {
        $ch = curl_init();
        if (ver_compare(phpversion(), '5.6') >= 0) {
            curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
        }
        if (!empty($extra['ip'])) {
            $extra['Host'] = $urlset['host'];
            $urlset['host'] = $extra['ip'];
            unset($extra['ip']);
        }
        curl_setopt($ch, CURLOPT_URL, $urlset['scheme'] . '://' . $urlset['host'] . ($urlset['port'] == '80' ? '' : ':' . $urlset['port']) . $urlset['path'] . $urlset['query']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        if ($post) {
            if (is_array($post)) {
                $filepost = false;
                foreach ($post as $name => $value) {
                    if (substr($value, 0, 1) == '@' || (class_exists('CURLFile') && $value instanceof CURLFile)) {
                        $filepost = true;
                        break;
                    }
                }
                if (!$filepost) {
                    $post = http_build_query($post);
                }
            }
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        if (!empty($GLOBALS['_W']['config']['setting']['proxy'])) {
            $urls = parse_url($GLOBALS['_W']['config']['setting']['proxy']['host']);
            curl_setopt($ch, CURLOPT_PROXY, "{$urls['host']}:{$urls['port']}");
            $proxytype = 'CURLPROXY_' . strtoupper($urls['scheme']);
            if (!empty($urls['scheme']) && defined($proxytype)) {
                curl_setopt($ch, CURLOPT_PROXYTYPE, constant($proxytype));
            } else {
                curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
                curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
            }
            if (!empty($GLOBALS['_W']['config']['setting']['proxy']['auth'])) {
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, $GLOBALS['_W']['config']['setting']['proxy']['auth']);
            }
        }
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSLVERSION, 1);
        if (defined('CURL_SSLVERSION_TLSv1')) {
            curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
        }
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1');
        if (!empty($extra) && is_array($extra)) {
            $headers = array();
            foreach ($extra as $opt => $value) {
                if (strexists($opt, 'CURLOPT_')) {
                    curl_setopt($ch, constant($opt), $value);
                } elseif (is_numeric($opt)) {
                    curl_setopt($ch, $opt, $value);
                } else {
                    $headers[] = "{$opt}: {$value}";
                }
            }
            if (!empty($headers)) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            }
        }
        $data = curl_exec($ch);
        $status = curl_getinfo($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if ($errno || empty($data)) {
            return error(1, $error);
        } else {
            return ihttp_response_parse($data);
        }
    }
    $method = empty($post) ? 'GET' : 'POST';
    $fdata = "{$method} {$urlset['path']}{$urlset['query']} HTTP/1.1\r\n";
    $fdata .= "Host: {$urlset['host']}\r\n";
    if (function_exists('gzdecode')) {
        $fdata .= "Accept-Encoding: gzip, deflate\r\n";
    }
    $fdata .= "Connection: close\r\n";
    if (!empty($extra) && is_array($extra)) {
        foreach ($extra as $opt => $value) {
            if (!strexists($opt, 'CURLOPT_')) {
                $fdata .= "{$opt}: {$value}\r\n";
            }
        }
    }
    $body = '';
    if ($post) {
        if (is_array($post)) {
            $body = http_build_query($post);
        } else {
            $body = urlencode($post);
        }
        $fdata .= 'Content-Length: ' . strlen($body) . "\r\n\r\n{$body}";
    } else {
        $fdata .= "\r\n";
    }
    if ($urlset['scheme'] == 'https') {
        $fp = fsockopen('ssl://' . $urlset['host'], $urlset['port'], $errno, $error);
    } else {
        $fp = fsockopen($urlset['host'], $urlset['port'], $errno, $error);
    }
    stream_set_blocking($fp, true);
    stream_set_timeout($fp, $timeout);
    if (!$fp) {
        return error(1, $error);
    } else {
        fwrite($fp, $fdata);
        $content = '';
        while (!feof($fp))
            $content .= fgets($fp, 512);
        fclose($fp);
        return ihttp_response_parse($content, true);
    }
}

function ihttp_response_parse($data, $chunked = false) {
    $rlt = array();
    $headermeta = explode('HTTP/', $data);
    if (count($headermeta) > 2) {
        $data = 'HTTP/' . array_pop($headermeta);
    }
    $pos = strpos($data, "\r\n\r\n");
    $split1[0] = substr($data, 0, $pos);
    $split1[1] = substr($data, $pos + 4, strlen($data));

    $split2 = explode("\r\n", $split1[0], 2);
    preg_match('/^(\S+) (\S+) (\S+)$/', $split2[0], $matches);
    $rlt['code'] = $matches[2];
    $rlt['status'] = $matches[3];
    $rlt['responseline'] = $split2[0];
    $header = explode("\r\n", $split2[1]);
    $isgzip = false;
    $ischunk = false;
    foreach ($header as $v) {
        $pos = strpos($v, ':');
        $key = substr($v, 0, $pos);
        $value = trim(substr($v, $pos + 1));
        if (is_array($rlt['headers'][$key])) {
            $rlt['headers'][$key][] = $value;
        } elseif (!empty($rlt['headers'][$key])) {
            $temp = $rlt['headers'][$key];
            unset($rlt['headers'][$key]);
            $rlt['headers'][$key][] = $temp;
            $rlt['headers'][$key][] = $value;
        } else {
            $rlt['headers'][$key] = $value;
        }
        if(!$isgzip && strtolower($key) == 'content-encoding' && strtolower($value) == 'gzip') {
            $isgzip = true;
        }
        if(!$ischunk && strtolower($key) == 'transfer-encoding' && strtolower($value) == 'chunked') {
            $ischunk = true;
        }
    }
    if($chunked && $ischunk) {
        $rlt['content'] = ihttp_response_parse_unchunk($split1[1]);
    } else {
        $rlt['content'] = $split1[1];
    }
    if($isgzip && function_exists('gzdecode')) {
        $rlt['content'] = gzdecode($rlt['content']);
    }

    $rlt['meta'] = $data;
    if($rlt['code'] == '100') {
        return ihttp_response_parse($rlt['content']);
    }
    return $rlt;
}

function ihttp_response_parse_unchunk($str = null) {
    if(!is_string($str) or strlen($str) < 1) {
        return false;
    }
    $eol = "\r\n";
    $add = strlen($eol);
    $tmp = $str;
    $str = '';
    do {
        $tmp = ltrim($tmp);
        $pos = strpos($tmp, $eol);
        if($pos === false) {
            return false;
        }
        $len = hexdec(substr($tmp, 0, $pos));
        if(!is_numeric($len) or $len < 0) {
            return false;
        }
        $str .= substr($tmp, ($pos + $add), $len);
        $tmp  = substr($tmp, ($len + $pos + $add));
        $check = trim($tmp);
    } while(!empty($check));
    unset($tmp);
    return $str;
}