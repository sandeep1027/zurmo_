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

    /**
     * Base class of report rules that assist with reporting data.  Extend this class to make
     * a set of ReportRules that is for a specific module or a combiniation of modules and/or models.
     */
    abstract class ReportRules
    {
        protected $modelClassName;

        public static function makeByModuleClassName($moduleClassName)
        {
            assert('is_string($moduleClassName)');
            $rulesClassName = $moduleClassName::getPluralCamelCasedName() . 'ReportRules';
            return new $rulesClassName();
        }

        /**
         * Returns metadata for use in the rules.  Will attempt to retrieve from cache if
         * available, otherwill retrieve from database and cache.
         * @see getDefaultMetadata()
         * @param $user The current user.
         * @returns An array of metadata.
         */
        public static function getMetadata()
        {
            $className = get_called_class();
            try
            {
                return GeneralCache::getEntry($className . 'Metadata');
            }
            catch (NotFoundException $e)
            {
            }
            $metadata = MetadataUtil::getMetadata($className);
            if (YII_DEBUG)
            {
                $className::assertMetadataIsValid($metadata);
            }
            GeneralCache::cacheEntry($className . 'Metadata', $metadata);
            return $metadata;
        }

        /**
         * Sets new metadata.
         * @param $metadata An array of metadata.
         * @param $user The current user.
         */
        public static function setMetadata(array $metadata)
        {
            $className = get_called_class();
            if (YII_DEBUG)
            {
                $className::assertMetadataIsValid($metadata);
            }
            MetadataUtil::setMetadata($className, $metadata);
            GeneralCache::cacheEntry($className . 'Metadata', $metadata);
        }

        /**
         * Returns default metadata for use in automatically generating the rules.
         */
        public static function getDefaultMetadata()
        {
            return array();
        }

        protected static function assertMetadataIsValid(array $metadata)
        {
        }

        public function relationIsReportedAsAttribute(RedBeanModel $model, $relation)
        {
            assert('is_string($relation)');
            $modelClassName = $model->getAttributeModelClassName($relation);
            $metadata       = static::getMetadata();
            if(isset($metadata[$modelClassName]) && isset($metadata[$modelClassName]['relationsReportedAsAttributes']) &&
            in_array($relation, $metadata[$modelClassName]['relationsReportedAsAttributes']))
            {
                return true;
            }
            if(in_array($model->getRelationModelClassName($relation),
                        array('OwnedCustomField',
                              'CustomField',
                              'OwnedMultipleValuesCustomField',
                              'MultipleValuesCustomField',
                              'CurrencyValue')))
            {
                return true;
            }
            return false;
        }

        public function attributeIsReportable(RedBeanModel $model, $attribute)
        {
            assert('is_string($attribute)');
            $modelClassName = $model->getAttributeModelClassName($attribute);
            $metadata = static::getMetadata();
            if(isset($metadata[$modelClassName]) && isset($metadata[$modelClassName]['nonReportable']) &&
            in_array($attribute, $metadata[$modelClassName]['nonReportable']))
            {
                return false;
            }
            return true;
        }

        public function getDerivedAttributeTypesData(RedBeanModel $model)
        {
            $derivedAttributeTypesData = array();
            $metadata = static::getMetadata();
            foreach (array_reverse(RuntimeUtil::getClassHierarchy(
                                   get_class($model), $model::getLastClassInBeanHeirarchy())) as $modelClassName)
            {
                if(isset($metadata[$modelClassName]) && isset($metadata[$modelClassName]['derivedAttributeTypes']))
                {
                    foreach($metadata[$modelClassName]['derivedAttributeTypes'] as $derivedAttributeType)
                    {

                        $elementClassName = $derivedAttributeType . 'Element';
                        $derivedAttributeTypesData[$derivedAttributeType] = array('label' => $elementClassName::getDisplayName());
                    }
                }
            }
            return $derivedAttributeTypesData;
        }

        //Rules say state uses X element for filter. or displayColumn for example
        //are there some derivedAttributes that are in fact availble on filter or other places beside just display
        //coolumns, if so need to think that through.
    }
?>