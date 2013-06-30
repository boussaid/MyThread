<?php
class MyThread_ControllerPublic_Thread extends XFCP_MyThread_ControllerPublic_Thread{
	public function actionIndex(){
        
		$response = parent::actionIndex();
        $visitor = XenForo_Visitor::getInstance();
		$options = XenForo_Application::get('options');
        $groupOptions = $options->usr_group;
        $ena = $options->enable_disable;
		if($ena == 1 AND !in_array($visitor['user_group_id'], $groupOptions)){
			if ($response instanceof XenForo_ControllerResponse_View){
				$mythread['myth'] = $this->getModelFromCache('MyThread_Model_MyThread')->getListMoreThread($response->params['thread']['user_id'], $options->limit_thread, $options->mythread_lenght_title, $options->mythread_timepost, $options->order_by);
				$response->params += array(
					'mythread' => $mythread
				);
			}
		}
       	return $response;
	}  
}