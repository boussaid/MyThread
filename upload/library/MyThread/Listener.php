<?php
class MyThread_Listener{
	public static function template_create($templateName, array &$params, XenForo_Template_Abstract $template){
        switch($templateName) {
            case 'thread_view':
            $template->preloadTemplate('MyThread');
            break;
        }
    }
	public static function template_hook($hookName, &$contents, array $hookParams, XenForo_Template_Abstract $template){
        if($hookName == 'message_content'){
			$ourTemplate = $template->create('MyThread', $template->getParams());
			$ourTemplate->setParam('message', $hookParams['message']);
			$contents .= $ourTemplate->render();
        }
    }
	public static function load_controllers($class, array &$extend)
    {
		if($class == 'XenForo_ControllerPublic_Thread'){
			$extend[] = 'MyThread_ControllerPublic_Thread';
		}
	}
    
    public static function renderOption(XenForo_View $view, $fieldPrefix, array $preparedOption, $canEdit)
    {
        return self::_render('option_list_option_checkbox', $view, $fieldPrefix, $preparedOption, $canEdit);
    }
    
    /*
     * Render list of users in addon's option
     */
    protected static function _render($templateName, XenForo_View $view, $fieldPrefix, array $preparedOption, $canEdit)
    {
        $preparedOption['formatParams'] = XenForo_Option_UserGroupChooser::getUserGroupOptions(
            $preparedOption['option_value']
        );

        return XenForo_ViewAdmin_Helper_Option::renderOptionTemplateInternal(
            $templateName, $view, $fieldPrefix, $preparedOption, $canEdit
        );
    }
}