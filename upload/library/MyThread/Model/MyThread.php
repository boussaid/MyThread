<?php 
class MyThread_Model_MyThread extends XenForo_Model{
	public function getListMoreThread($messageId, $limit, $lenght_title, $time_format, $orderby){
		switch($orderby){
			case 'post_date': $my_ord = 'post_date DESC'; break;
			case 'random': $my_ord = 'RAND() DESC'; break;
			case 'view_count': $my_ord = 'view_count DESC'; break;
		}
		$options = XenForo_Application::get('options');
        $not_infor = $options->not_infor;
		$data = array();
		$db = XenForo_Application::get('db');
		$data = $db->fetchAll("
			SELECT *
			FROM xf_thread AS xt
			WHERE xt.user_id = '{$messageId}' 
            AND xt.node_id NOT IN ('" . implode("','",$not_infor) . "')          
			ORDER BY $my_ord 
			LIMIT 0, $limit 
		");
		foreach($data as $key=>$dt){
			$dt['title'] = $this->subString($dt['title'],$lenght_title);
			if($time_format == 'none'){
				unset($dt['post_date']);
			}else{
				$dt['post_date'] = date($time_format, $dt['post_date']);
			}
			$data[$key] = $dt;
		}
		return $data;
	}
	private function subString($str, $len, $charset='UTF-8'){
		$str = html_entity_decode($str, ENT_QUOTES, $charset);
		if(mb_strlen($str, $charset)> $len){
			$arr = explode(' ', $str);
			$str = mb_substr($str, 0, $len, $charset);
			$arrRes = explode(' ', $str);
			$last = $arr[count($arrRes)-1];
			unset($arr);
			if(strcasecmp($arrRes[count($arrRes)-1], $last))
			{
				unset($arrRes[count($arrRes)-1]);
			}
		return implode(' ', $arrRes)." ...";
		}
		return $str;
	}
}