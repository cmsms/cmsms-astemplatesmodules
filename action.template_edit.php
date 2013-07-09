<?php
    /**
     *
     * Project: ASModulesTemplates
     * Created on: 08/07/13 15:59
     *
     * Copyright 2013 Atom Seeds - Jean-Christophe Cuvelier <jcc@atomseeds.com>
     */

    if (!cmsms()) exit;
    if (!$this->CheckAccess()) // Restrict to admin panel and users with permission
    {
        return $this->DisplayErrorPage($id, $params, $returnid, $this->Lang('accessdenied'));
    }

    $form = new CMSForm($this->GetName(), $id, 'template_edit', $returnid);
    $form->setButtons(array('submit', 'apply', 'cancel'));
    $form->setMethod('post');
    $form->setWidget('module_name', 'hidden', array('validators' => array('not_empty' => true)));

    if ($module = cms_utils::get_module($form->getWidget('module_name')->getValue())) {

        $module_templates = new ASModuleTemplates($module);

        $form->setWidget('template', 'text', array('validators' => array('not_empty' => true)));
        $form->setWidget('code', 'codearea');

        $form->setWidget('restore_template_from', 'select', array( 'include_custom' => $this->lang('select one'),   'values' => ASTemplates::getTemplatesFromModule($module)));
        $form->setWidget('default_template_for', 'select', array(  'include_custom' => $this->lang('select one'),   'values' => ASTemplates::getTemplatesFromModule($module)));

        $action = $module_templates->isDefaultTemplate($form->getWidget('template')->getValue());
        if ($form->getWidget('default_template_for')->isEmpty() && ($action !== false))
        {
            $form->getWidget('default_template_for')->setValue($action);
        }

        if (!$form->getWidget('template')->isEmpty()) {
            if ($form->getWidget('code')->isEmpty() && !$form->isSent()) {

                $tpl = $form->getWidget('template')->getValue();
                $form->getWidget('code')->setValues($module->getTemplate($tpl));
            }
        } else {
            $form->getWidget('code')->setValues('');
        }

        if ($form->isSent()) {

            if (($restore = $form->getWidget('restore_template_from')->getValue()) && ($restore != '')) {
                $form->getWidget('restore_template_from')->setValues('');
                $form->getWidget('code')->setValue(ASTemplates::getTemplateFromModuleFile($module, $restore));
            }

            $form->process();

            if (!$form->hasErrors()) {

                if($default = $form->getWidget('default_template_for')->getValue())
                {
                    if($default == '')
                    {
                        $module_templates->RemoveDefaultTemplate($form->getWidget('template')->getValue());
                    }
                    else
                    {
                        $module_templates->AddDefaultTemplate($default, $form->getWidget('template')->getValue());
                        $form->getWidget('default_template_for')->setValues($default);
                    }
                }

                $module->SetTemplate($form->getWidget('template')->getValue(), $form->getWidget('code')->getValue());

                if ($form->isSubmitted()) {
                    return $this->Redirect($id, 'defaultadmin', $returnid, array('active_tab' => 'templates'));
                }
            }
        }

        if ($form->isCancelled()) {
            return $this->Redirect($id, 'defaultadmin', $returnid, array('active_tab' => 'templates'));
        }

        $smarty->assign('form', $form);

        echo $this->ProcessTemplate('admin.template_edit.tpl');
    } else {
        return $this->DisplayErrorPage($id, $params, $returnid, $this->Lang('module not found'));
    }



