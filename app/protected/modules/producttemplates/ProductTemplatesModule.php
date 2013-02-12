<?php
    /*********************************************************************************
     * Zurmo is a customer relationship management program developed by
     * Zurmo, Inc. Copyright (C) 2012 Zurmo Inc.
     *
     * Zurmo is free software; you can redistribute it and/or modify it under
     * the terms of the GNU General Public License version 3 as published by the
     * Free Software Foundation with the addition of the following permission added
     * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
     * IN WHICH THE COPYRIGHT IS OWNED BY ZURMO, ZURMO DISCLAIMS THE WARRANTY
     * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
     *
     * Zurmo is distributed in the hope that it will be useful, but WITHOUT
     * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
     * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
     * details.
     *
     * You should have received a copy of the GNU General Public License along with
     * this program; if not, see http://www.gnu.org/licenses or write to the Free
     * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
     * 02110-1301 USA.
     *
     * You can contact Zurmo, Inc. with a mailing address at 113 McHenry Road Suite 207,
     * Buffalo Grove, IL 60089, USA. or at email address contact@zurmo.com.
     ********************************************************************************/

    class ProductTemplatesModule extends SecurableModule
    {
        const RIGHT_CREATE_PRODUCTTEMPLATES = 'Create ProductTemplates';
        const RIGHT_DELETE_PRODUCTTEMPLATES = 'Delete ProductTemplates';
        const RIGHT_ACCESS_PRODUCTTEMPLATES = 'Access ProductTemplates Tab';

        public function getDependencies()
        {
            return array(
                'configuration',
                'zurmo',
            );
        }

        public function getRootModelNames()
        {
            return array('ProductTemplate');
        }

        public static function getUntranslatedRightsLabels()
        {
            $labels                              = array();
            $labels[self::RIGHT_CREATE_PRODUCTTEMPLATES] = 'Create ProductTemplatesModulePluralLabel';
            $labels[self::RIGHT_DELETE_PRODUCTTEMPLATES] = 'Delete ProductTemplatesModulePluralLabel';
            $labels[self::RIGHT_ACCESS_PRODUCTTEMPLATES] = 'Access ProductTemplatesModulePluralLabel Tab';
            return $labels;
        }

        public static function getDefaultMetadata()
        {
            $metadata = array();
            $metadata['global'] = array(
                'designerMenuItems' => array(
                    'showFieldsLink' => true,
                    'showGeneralLink' => true,
                    'showLayoutsLink' => true,
                    'showMenusLink' => true,
                ),
                'globalSearchAttributeNames' => array(
                    'name',
                ),
                'tabMenuItems' => array(
                    array(
                        'label' => 'ProductTemplatesModulePluralLabel',
                        'url'   => array('/producttemplates/default'),
                        'right' => self::RIGHT_ACCESS_PRODUCTTEMPLATES,
                    ),
                ),
                'shortcutsCreateMenuItems' => array(
                    array(
                        'label' => 'ProductTemplatesModuleSingularLabel',
                        'url'   => array('/producttemplates/default/create'),
                        'right' => self::RIGHT_CREATE_PRODUCTTEMPLATES,
                    ),
                )
            );
            return $metadata;
        }

        public static function getPrimaryModelName()
        {
            return 'ProductTemplate';
        }

        public static function getAccessRight()
        {
            return self::RIGHT_ACCESS_PRODUCTTEMPLATES;
        }

        public static function getCreateRight()
        {
            return self::RIGHT_CREATE_PRODUCTTEMPLATES;
        }

        public static function getDeleteRight()
        {
            return self::RIGHT_DELETE_PRODUCTTEMPLATES;
        }

        public static function getDefaultDataMakerClassName()
        {
            return 'ProductTemplatesDefaultDataMaker';
        }

        public static function getDemoDataMakerClassName()
        {
            return 'ProductTemplatesDemoDataMaker';
        }

        public static function getGlobalSearchFormClassName()
        {
            return 'ProductTemplatesSearchForm';
        }

        public static function hasPermissions()
        {
            return true;
        }
    }
?>