<?php
/**
  * 
  * Project: ASModulesTemplates
  * Created on: 08/07/13 14:51
  *
  * Copyright 2013 Atom Seeds - Jean-Christophe Cuvelier <jcc@atomseeds.com> 
  */

class ASTemplates {

    // TOOLS

    public static function getModulesList($full = false)
    {
        $modop = ModuleOperations::get_instance();

        $modules = $modop->FindAllModules();

        $array = array();

        foreach($modules as $module)
        {
            if(($mod = cms_utils::get_module($module)) && ($mod->IsPluginModule()))
            {
                if($full)
                {
                    $array[$module] = $mod;
                }
                else
                {
                    $array[$module] = $mod->getFriendlyName();
                }

            }
        }

        return $array;
    }

    /**
     * @param $module CMSModule The module from which to extract template
     */

    public static function getTemplatesFromModule($module)
    {
        $root = $module->GetModulePath() . DIRECTORY_SEPARATOR . 'templates';

        $templates = array();

        if(is_dir($root))
        {
            if($files = scandir($root))
            {
                foreach($files as $file)
                {
//                    var_dump($files);
                    if((strpos($file, 'frontend.') === 0) && (strpos(strrev($file), strrev('.tpl')) === 0))
                    {
                        $name = substr(substr($file, 9), 0, -4);
                        $templates[$name] = $name;
                    }
                }
            }
        }

        return $templates;
    }

    /**
     * @param CMSModule $module
     * @param string $template
     *
     * @return string
     */

    public static function getTemplateFromModuleFile($module, $template)
    {
        $file = $module->GetModulePath() . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'frontend.' . $template . '.tpl';

        $template = '';

        if(is_file($file))
        {
            $template = file_get_contents($file);
        }

        return $template;

    }
}