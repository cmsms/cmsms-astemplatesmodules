<?php
/**
  * 
  * Project: ASModulesTemplates
  * Created on: 08/07/13 14:45
  *
  * Copyright 2013 Atom Seeds - Jean-Christophe Cuvelier <jcc@atomseeds.com> 
  */

    if (!cmsms()) exit;

    if (!$this->CheckAccess()) {
        return $this->DisplayErrorPage($id, $params, $returnid, $this->Lang('accessdenied'));
    }

    $form = new CMSForm($this->getName(), $id, 'defaultadmin', $returnid);
    $form->setButtons(array('select'));
    $form->setLabel('select', $this->lang('select'));
    $form->setWidget('module', 'select', array('values' => ASTemplates::getModulesList(), 'label' => $this->Lang('module'),     'include_custom' => $this->lang('select one'), 'user_preference' => true));


    if($form->isSent())
    {
        $form->process();
    }

    $smarty->assign('module_form', $form);


    if($module_name = $form->getWidget('module')->getValue())
    {
        $module = cms_utils::get_module($module_name);

        if($module)
        {
            $module_templates = new ASModuleTemplates($module);

            $list_templates = $module->ListTemplates();
            $smarty->assign('templates', $templates);

            $templates = array();
            foreach($list_templates as $template) {
                $row = array(
                    'titlelink' => $this->CreateLink($id, 'template_edit', $returnid, $template, array('module_name' => $module_name, 'template' => $template), '', false, false, 'class="itemlink"'),
                    'deletelink' => $this->CreateLink($id, 'template_delete', $returnid, cmsms()->variables['admintheme']->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon'), array('module_name' => $module_name, 'template' => $template), $this->lang('are you sure you want to delete this template')),
                    'editlink' => $this->CreateLink($id, 'template_edit', $returnid, cmsms()->variables['admintheme']->DisplayImage('icons/system/edit.gif', $template, '', '', 'systemicon'), array('module_name' => $module_name, 'template' => $template))
                );

                if ($module_templates->isDefaultTemplate($template) !== false)
                {
                    $row['default'] = $this->lang('default template for', $module_templates->isDefaultTemplate($template));
                }
                else
                {
                    $row['default'] = '';
                }

                $templates[] = $row;
            }
            $smarty->assign('templates', $templates);

            $smarty->assign('add_templates_link', $this->CreateLink($id, 'template_edit', $returnid, $this->Lang('add template'), array('module_name' => $module_name)));
            $smarty->assign('add_templates_icon', $this->CreateLink($id, 'template_edit', $returnid, $gCms->variables['admintheme']->DisplayImage('icons/system/newobject.gif', $this->Lang('add template'), '', '', 'systemicon')), array('module_name' => $module_name));
        }
    }

    echo $smarty->fetch($this->GetFileResource('admin.default.tpl'),'cacheid' . time(),'compileid');