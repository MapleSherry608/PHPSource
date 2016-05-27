<div class="survey_topic"><{$survey_datas_1.topic}></div>
<div class="survey_item">
	<form method="post" action="<{$app}>/survey/mod_result">
	<ul>
    	<li><input type="radio" name="item" value="1" />&nbsp;<{$survey_datas_1.item1}></li>
        <li><input type="radio" name="item" value="2" />&nbsp;<{$survey_datas_1.item2}></li>
        <li><input type="radio" name="item" value="3" />&nbsp;<{$survey_datas_1.item3}></li>
        <li><input type="radio" name="item" value="4" />&nbsp;<{$survey_datas_1.item4}></li>
        <li><input type="radio" name="item" value="5" />&nbsp;<{$survey_datas_1.item5}></li>
        <li><input type="submit" id="survey_submit" value="提交" />&nbsp;<input type="button" id="survey_result" data="<{$survey_datas_1.id}>" value="结果" /></li>
    </ul>
    <input type="hidden" name="id" value="<{$survey_datas_1.id}>" />
    </form>
</div>