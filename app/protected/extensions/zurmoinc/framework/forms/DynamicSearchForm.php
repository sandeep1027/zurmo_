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
     * Extend this class when a searchForm should allow dynamic searches in advanced search. This means the user
     * can add any field as a search parameter.
     */
    abstract class DynamicSearchForm extends SearchForm
    {
        public $dynamicStructure;

        public $dynamicClauses;

        public function rules()
        {
            return array_merge(parent::rules(), array(
                               array('dynamicStructure', 'safe'),
                               array('dynamicStructure',   'validateDynamicStructure', 'on' => 'validateDynamic'),
                               array('dynamicClauses',   'safe'),
                               array('dynamicClauses',   'validateDynamicClauses', 'on' => 'validateDynamic'),
            ));
        }

        public function attributeLabels()
        {
            return array_merge(parent::attributeLabels(), array(
                               'dynamicStructure' => Yii::t('Default', 'Clause Ordering'),
            ));
        }

        public function validateDynamicStructure($attribute, $params)
        {
            //todo: test for valid structure math.
            //CalculatedNumberUtil::isFormulaValid <- something like this maybe
            //$this->addError('dynamicStructure', Yii::t('Default', 'The structure is invalid'));
        }

        public function validateDynamicClauses($attribute, $params)
        {
            if($this->$attribute != null)
            {
                $dynamicSearchAttributes = SearchUtil::getSearchAttributesFromSearchArray($this->$attribute);
                $sanitizedData           = DataUtil::sanitizeDataByDesignerTypeForSavingModel($this, $dynamicSearchAttributes);
                foreach($sanitizedData as $key => $rowData)
                {
                    $structurePosition = $rowData['structurePosition'];
                    if($rowData['attributeIndexOrDerivedType'] == null)
                    {
                        $this->addError('dynamicClauses', Yii::t('Default', 'You must select a field for row {rowNumber}',
                        array('{rowNumber}' => $structurePosition)));
                    }
                    else
                    {
                        unset($rowData['attributeIndexOrDerivedType']);
                        unset($rowData['structurePosition']);
                        $metadataAdapter = new SearchDataProviderMetadataAdapter(
                            $this,
                            Yii::app()->user->userModel->id,
                            $rowData
                        );
                        $metadata = $metadataAdapter->getAdaptedMetadata();
                        if(count($metadata['clauses']) == 0)
                        {
                            $this->addError('dynamicClauses', Yii::t('Default', 'You must select a value for row {rowNumber}',
                            array('{rowNumber}' => $structurePosition)));
                        }
                    }
                }
            }
        }
    }
?>