<?php
    /**
     *
     * Project: ASModulesTemplates
     * Created on: 04/07/13 16:09
     *
     * Copyright 2013 Atom Seeds - Jean-Christophe Cuvelier <jcc@atomseeds.com>
     */

    class ASModulesTemplates extends CMSModule
    {

        public function getName()
        {
            return 'ASModulesTemplates';
        }

        public function getFriendlyName()
        {
            return $this->lang('Modules Templates');
        }
        public function GetAuthor()            	{ return 'Jean-Christophe Cuvelier'; }

        public function GetAuthorEmail()       	{ return 'jcc@atomseeds.com'; }

        public function getVersion()
        {
            return '0.0.2';
        }

        public function MinimumCMSVersion()
        {
            return '1.11';
        }

//    public function IsPluginModule()   {   return true;    }
        public function GetDependencies()
        {
            return array('CMSForms' => '1.10.7');
        }

//    public function AllowSmartyCaching()    {   return true; }
//    public function LazyLoadFrontend()  {   return TRUE;    }

        public function GetAdminSection()
        {
            return 'layout';
        }

        public function HasAdmin()             	{ return true; }
        public function VisibleToAdminUser()   	{ return $this->CheckAccess(); }
        public function CheckAccess()          	{ return $this->CheckPermission('Modify Templates'); }

    }