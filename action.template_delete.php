<?php
    /**
     *
     * Project: ASModulesTemplates
     * Created on: 09/07/13 10:09
     *
     * Copyright 2013 Atom Seeds - Jean-Christophe Cuvelier <jcc@atomseeds.com>
     */

    if (!cmsms()) exit;
    if (!$this->CheckAccess()) // Restrict to admin panel and users with permission
    {
        return $this->DisplayErrorPage($id, $params, $returnid, $this->Lang('accessdenied'));
    }

    if (isset($params['template']) && !empty($params['template']) && isset($params['module_name']) && !empty($params['module_name'])) {

        $module = cms_utils::get_module($params['module_name']);

        if($module)
        {
            $module_templates = new ASModuleTemplates($module);
            $module->DeleteTemplate($params['template']);
            $module_templates->RemoveDefaultTemplate($params['template']);
        }
    }

    $this->Redirect($id, 'defaultadmin', $returnid);
    exit;