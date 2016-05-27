<?php
function getOption($goodsid = 0, $optionid = 0)
{
    $uniacid                    = 0;
    $shop_goods_option          =M('zxin_shop_goods_option');
    $goods_option = $shop_goods_option->where(array('id' => $optionid, 'uniacid' => $uniacid, 'goodsid' => $goodsid))->find();
	return $goods_option;
}
