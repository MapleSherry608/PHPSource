<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<{$public}>/css/global.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/main.css" rel="stylesheet" type="text/css" />
<link href="<{$public}>/css/weebox.css" rel="stylesheet" type="text/css" />
<link href="<{$public}>/css/date_input.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<{$public}>/js/jquery.js"></script>
<script type="text/javascript" src="<{$res}>/js/common.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.bgiframe.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.weebox.js"></script>
<script type="text/javascript" src="<{$public}>/js/highcharts.js"></script> 
<script type="text/javascript" src="<{$public}>/js/grid.js"></script>
<script src="<{$public}>/js/jquery.date_input.js" type='text/javascript' language="javascript"></script>
<title>main</title>
<script language="javascript" type="text/javascript"> 

function autoHeight() { 
	var h = $(window).height(); var h_old = 300; if (h > h_old) { $(".info").css('height', h-95); } else { return false; } 
}
$(function() { 
  	autoHeight();
	 $(window).resize(autoHeight); 
	
	$(".date_input").date_input(); 
	
	
	
	chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'result',          //放置图表的容器
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    defaultSeriesType: 'column'   //图表类型line, spline, area, areaspline, column, bar, pie , scatter 
                },
                title: {
                    text: '会员统计图表'
                }, 
                xAxis: {//X轴数据
                    categories: [<{$result_class}>],
                    labels: {
                        rotation: 0, //字体倾斜
                        align: 'right',
                        style: { font: 'normal 12px microsoft yahei' }
                    }
                },
                yAxis: {//Y轴显示文字
                    title: {
                        text: '个数'
                    }
					
                },
                tooltip: {
                    enabled: true,
                    formatter: function() {
                        return '<b>' + this.x + '</b><br/>' + this.series.name + ': ' + Highcharts.numberFormat(this.y, 0) + "个";
                    }
                },
                plotOptions: {
                    column: {
                        dataLabels: {
                            enabled: true
                        },
                        enableMouseTracking: true//是否显示title
                    }
                },
                series: [
				
					{
						name: '总计',
						data: [<{$result_total_data}>]
					} ,
					<{if !$is_search}>
					{
						name: '今年',
						data: [<{$result_data}>]
					} ,
					
					<{else}>
					{
						name: '<{$condition}>',
						data: [<{$result_data}>]
					} 
					<{/if}>
				]
                });

				
				
})



</script> 
</head>

<body>

<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;用户统计管理</div></div>
<form action="<{$app}>/statistic/index" method="get">
<div class="class_bar">
	<div class="left">搜索内容：<{if $is_search}><{$condition}><{else}>所有时间列表<{/if}></div>
    <div class="right" style="margin-right:70px;">
     搜索条件:&nbsp;<input type="text"  name="start_time" id="start_time" class="date_input" style="width:130px" onClick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" readonly/>&nbsp;至&nbsp;<input type="text" name="end_time" id="end_time" class="date_input" style="width:130px" onClick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" readonly/>&nbsp;&nbsp;<input type="submit" value="搜索" id="search" />
     </div>
     <div class="clear"></div>
</div>
</form>
<div class="info" style="overflow-y:scroll;">
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
      <tr>
        <td width="33%" align="center" class="title_color">今日新增会员</td>
        <td width="33%" align="center" class="title_color">本月新增会员</td>
        <td width="33%" align="center" class="title_color">会员总数</td>
      </tr>
      <tr>
        <td align="center" class="body_color"><{$result.today}></td>
        <td align="center" class="body_color"><{$result.month}></td>
        <td align="center" class="body_color"><{$result.total}></td>
      </tr>
	  
      </table>
	  <div id="result"></div>
</div>
</body>
</html>
