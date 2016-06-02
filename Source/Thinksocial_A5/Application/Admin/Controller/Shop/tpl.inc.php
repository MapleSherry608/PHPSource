<?php
$tpl = trim(I('tpl'));
if ($tpl == 'option') {
    $tag = random(32);
    $this->assign('tag',$tag);
    $this->display('Shop/tpl/option');
} else {
    if ($tpl == 'spec') {
        $spec = array("id" => random(32), "title" => I('title'));
        $this->assign('spec',$spec);
        $this->display('Shop/tpl/spec');
    } else {
        if ($tpl == 'specitem') {
            $spec = array("id" => I('specid'));
            $specitem = array("id" => random(32), "title" => I('title'), "show" => 1);
            $this->assign('spec',$spec);
            $this->assign('specitem',$specitem);
            $this->display('Shop/tpl/spec_item');
        } else {
            if ($tpl == 'param') {
                $tag = random(32);
                $this->assign('tag',$tag);
                $this->display('Shop/tpl/param');
            }
        }
    }
}