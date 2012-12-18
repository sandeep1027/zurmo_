<?php
    /**********************************************************************************
     * Zurmo is a customer relationship management program developed by
     * Zurmo, Inc. Copyright (C) 2011 Zurmo Inc.
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
     *********************************************************************************/

    class Animal extends Item
    {
        public function __toString()
        {
            if (trim($this->name) == '')
            {
                return Yii::t('Default', '(Unnamed)');
            }
            return $this->name;
        }

        public static function getModuleClassName()
        {
            return 'AnimalsModule';
        }

        /**
         * Returns the display name for the model class.
         * @return dynamic label name based on module.
         */
        protected static function getLabel()
        {
            return 'AnimalsModuleSingularLabel';
        }

        /**
         * Returns the display name for plural of the model class.
         * @return dynamic label name based on module.
         */
        protected static function getPluralLabel()
        {
            return 'AnimalsModulePluralLabel';
        }

        public static function canSaveMetadata()
        {
            return true;
        }

        public static function getDefaultMetadata()
{
    $metadata = parent::getDefaultMetadata();
    $metadata[__CLASS__] = array(
        'members' => array(
            'name',
            'description',
            'cust_checkbox',
            'cust_date',
            'cust_datetime',
            'cust_decimal',
            'cust_integer',
            'cust_phone',
            'cust_text',
            'cust_textarea',
            'cust_url',
        ),
        'relations' => array(
            'type'          => array(RedBeanModel::HAS_ONE,   'OwnedCustomField', RedBeanModel::OWNED),
            'cust_currency' => array(RedBeanModel::HAS_ONE,   'CurrencyValue', RedBeanModel::OWNED),
            'cust_picklist' => array(RedBeanModel::HAS_ONE,   'OwnedCustomField', RedBeanModel::OWNED),
            'cust_radiopicklist' => array(RedBeanModel::HAS_ONE,   'OwnedCustomField', RedBeanModel::OWNED),
        ),
        'rules' => array(
            array('name',           'required'),
            array('name',           'type',           'type'  => 'string'),
            array('name',           'length',         'max'   => 100),
            array('description',    'type',           'type'  => 'string'),
            array('cust_checkbox',  'type',           'type'  => 'boolean'),
            array('cust_checkbox',  'default',        'value' => 1),
            array('cust_date',      'type',           'type'  => 'date'),
            array('cust_date',      'dateTimeDefault','value' => 2),
            array('cust_datetime',  'type',           'type'  => 'datetime'),
            array('cust_datetime',  'dateTimeDefault','value' => 2),
            array('cust_decimal',   'default',        'value' => 1),
            array('cust_decimal',   'length',         'max'   => 18),
            array('cust_decimal',   'numerical',      'precision' => 2),
            array('cust_decimal',   'type',           'type'   => 'float'),
            array('cust_integer',   'length',         'max'    => 11),
            array('cust_integer',   'numerical',      'max'    => 9999, 'min' => 0 ),
            array('cust_integer',   'type',           'type'   => 'integer'),
            array('cust_picklist',  'default',        'value'  => 'Value one'),
            array('cust_phone',     'length',         'max'    => 20),
            array('cust_phone',     'type',           'type'   => 'string'),
            array('cust_text',      'length',         'max'    => 255),
            array('cust_text',      'type',           'type'   => 'string'),
            array('cust_textarea',  'type',           'type'   => 'string'),
            array('cust_url',       'length',         'max'    => 255),
            array('cust_url',       'url'),
        ),
        'elements' => array(
            'description'     => 'TextArea',
            'cust_checkbox'   => 'CheckBox',
            'cust_currency'   => 'CurrencyValue',
            'cust_date'       => 'Date',
            'cust_datetime'   => 'DateTime',
            'cust_decimal'    => 'Decimal',
            'cust_integer'    => 'Integer',
            'cust_picklist'   => 'DropDown',
            'cust_phone'      => 'Phone',
            'cust_radiopicklist'     => 'RadioDropDown',
            'cust_text'       => 'Text',
            'cust_textarea'   => 'TextArea',
            'cust_url'        => 'Url',
        ),
        'customFields' => array(
            'type'               => 'AnimalType',
            'cust_picklist'      => 'Cust_picklist',
            'cust_radiopicklist' => 'Cust_radiopicklist',
        ),
        'defaultSortAttribute' => 'name',
        'noAudit' => array(
            'description',
            'cust_date',
            'cust_datetime',
            'cust_decimal',
            'cust_integer',
            'cust_picklist',
            'cust_phone',
            'cust_radiopicklist',
            'cust_text',
            'cust_textarea',
            'cust_url'
        ),
        'labels' => array(
            'cust_checkbox'  => array('en' => 'Check Box'),
            'cust_currency'  => array('en' => 'Currency'),
            'cust_date'  => array('en' => 'Date'),
            'cust_datetime'  => array('en' => 'Date Time'),
            'cust_decimal'  => array('en' => 'Decimal'),
            'cust_integer'  => array('en' => 'Integer'),
            'cust_picklist'  => array('en' => 'Pick List'),
            'cust_phone'  => array('en' => 'Phone'),
            'cust_radiopicklist'  => array('en' => 'Radio Pick List'),
            'cust_text'  => array('en' => 'Text'),
            'cust_textarea'  => array('en' => 'Text Area'),
            'cust_url'  => array('en' => 'URL'),
        ),
    );
    return $metadata;
}

        public static function isTypeDeletable()
        {
            return true;
        }
    }
?>
