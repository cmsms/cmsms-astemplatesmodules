<?php
/**
  * 
  * Project: ASModulesTemplates
  * Created on: 08/07/13 17:47
  *
  * Copyright 2013 Atom Seeds - Jean-Christophe Cuvelier <jcc@atomseeds.com> 
  */

class ASModuleTemplates {

    /**
     * @var CMSModule
     */
    public $module;

    /**
     * @param $module CMSModule
     */

    public function __construct($module)
    {
        $this->module = $module;
    }

    public function GetDefaultTemplates() {
        $array = unserialize($this->module->GetPreference('default_templates'));
        if (is_array($array))
        {
            return $array;
        }
        return array();
    }

    public function SetDefaultTemplates($list = array())  {
        return $this->module->SetPreference('default_templates', serialize($list));
    }

    public function AddDefaultTemplate($action, $template) {
        $list = $this->GetDefaultTemplates();
        $list[$action] = $template;
        $this->SetDefaultTemplates($list);
    }

    public function GetDefaultTemplate($action) {
        $list = $this->GetDefaultTemplates();
        if (!is_array($list)) $list = array();
        if (array_key_exists($action, $list)) // TODO: Possible problem with list
        {
            return $list[$action];
        }
        else
        {
            return false;
        }
    }

    public function isDefaultTemplate($template)  {
        $list = $this->GetDefaultTemplates();
        $action = array_search($template, $list);
        if($action !== false)
        {
            return $action;
        }
        return false;
    }

    public function removeDefaultTemplate($template)  {
        $list = $this->GetDefaultTemplates();
        $action = array_search($template, $list);
        if($action !== false)
        {
            unset($list[$action]);
            $this->SetDefaultTemplates($list);
        }
        return false;
    }
}