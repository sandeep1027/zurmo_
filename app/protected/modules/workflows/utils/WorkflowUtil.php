<?php
    /*********************************************************************************
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
     ********************************************************************************/

    /**
     * Helper class for working with Workflow objects
     */
    class WorkflowUtil
    {
        /**
         * @param $type
         * @return null | string
         */
        public static function renderNonEditableTypeStringContent($type)
        {
            assert('is_string($type)');
            $typesAndLabels = Workflow::getTypeDropDownArray();
            if(isset($typesAndLabels[$type]))
            {
                return $typesAndLabels[$type];
            }
        }

        /**
         * @param $moduleClassName
         * @return null | string
         */
        public static function renderNonEditableModuleStringContent($moduleClassName)
        {
            assert('is_string($moduleClassName)');
            $modulesAndLabels = Workflow::getWorkflowSupportedModulesAndLabelsForCurrentUser();
            if(isset($modulesAndLabels[$moduleClassName]))
            {
                return $modulesAndLabels[$moduleClassName];
            }
        }

        public static function resolveDataAndLabelsForTimeTriggerAvailableAttributes($moduleClassName, $modelClassName,
                                                                                     $workflowType)
        {
            assert('is_string($moduleClassName)');
            assert('is_string($modelClassName)');
            assert('is_string($workflowType)');
            $modelToWorkflowAdapter             = ModelRelationsAndAttributesToWorkflowAdapter::
                make($moduleClassName, $modelClassName, $workflowType);
            if(!$modelToWorkflowAdapter instanceof ModelRelationsAndAttributesToByTimeWorkflowAdapter)
            {
                throw new NotSupportedException();
            }
            $attributes     = $modelToWorkflowAdapter->getAttributesForTimeTrigger();
            $dataAndLabels  = array('' => Zurmo::t('Core', '(None)'));
            return array_merge($dataAndLabels, WorkflowUtil::renderDataAndLabelsFromAdaptedAttributes($attributes));
        }

        /**
         * Given an array of attributes generated from $modelToWorkflowAdapter->getAttributesForTimeTrigger()
         * return an array indexed by the attribute and the value is the label
         * @param array $attributes
         * @return array
         */
        public static function renderDataAndLabelsFromAdaptedAttributes($attributes)
        {
            assert('is_array($attributes)');
            $dataAndLabels = array();
            foreach($attributes as $attribute => $data)
            {
                $dataAndLabels[$attribute] = $data['label'];
            }
            return $dataAndLabels;
        }

        public static function resolvePositiveDurationAsDistanceFromPointData(& $data, $includeHours)
        {
            assert('is_bool($includeHours)');
            if($includeHours)
            {
                $data[14400] = Zurmo::t('WorkflowsModule', '{n} hour from now|{n} hours from now', array(4));
                $data[28800] = Zurmo::t('WorkflowsModule', '{n} hour from now|{n} hours from now', array(8));
                $data[43200] = Zurmo::t('WorkflowsModule', '{n} hour from now|{n} hours from now', array(12));
            }
            $data[86400]    = Zurmo::t('WorkflowsModule', '{n} day from now|{n} days from now', array(1));
            $data[172800]   = Zurmo::t('WorkflowsModule', '{n} day from now|{n} days from now', array(2));
            $data[259200]   = Zurmo::t('WorkflowsModule', '{n} day from now|{n} days from now', array(3));
            $data[345600]   = Zurmo::t('WorkflowsModule', '{n} day from now|{n} days from now', array(4));
            $data[432000]   = Zurmo::t('WorkflowsModule', '{n} day from now|{n} days from now', array(5));
            $data[864000]   = Zurmo::t('WorkflowsModule', '{n} day from now|{n} days from now', array(10));
            $data[604800]   = Zurmo::t('WorkflowsModule', '{n} week from now|{n} weeks from now', array(1));
            $data[1209600]  = Zurmo::t('WorkflowsModule', '{n} week from now|{n} weeks from now', array(2));
            $data[1814400]  = Zurmo::t('WorkflowsModule', '{n} week from now|{n} weeks from now', array(3));
            $data[2592000]  = Zurmo::t('WorkflowsModule', '{n} day from now|{n} days from now', array(30));
            $data[5184000]  = Zurmo::t('WorkflowsModule', '{n} day from now|{n} days from now', array(60));
            $data[7776000]  = Zurmo::t('WorkflowsModule', '{n} day from now|{n} days from now', array(90));
            $data[10368000] = Zurmo::t('WorkflowsModule', '{n} day from now|{n} days from now', array(120));
            $data[12960000] = Zurmo::t('WorkflowsModule', '{n} day from now|{n} days from now', array(150));
            $data[15552000] = Zurmo::t('WorkflowsModule', '{n} day from now|{n} days from now', array(180));
            $data[31104000] = Zurmo::t('WorkflowsModule', '{n} year from now|{n} years from now', array(1));
        }

        public static function resolvePositiveDurationData(& $data, $includeHours)
        {
            assert('is_bool($includeHours)');
            if($includeHours)
            {
                $data[14400] = Zurmo::t('WorkflowsModule', 'for {n} hour|for {n} hours', array(4));
                $data[28800] = Zurmo::t('WorkflowsModule', 'for {n} hour|for {n} hours', array(8));
                $data[43200] = Zurmo::t('WorkflowsModule', 'for {n} hour|for {n} hours', array(12));
            }
            $data[86400]    = Zurmo::t('WorkflowsModule', 'for {n} day|{n} days', array(1));
            $data[172800]   = Zurmo::t('WorkflowsModule', 'for {n} day|{n} days', array(2));
            $data[259200]   = Zurmo::t('WorkflowsModule', 'for {n} day|{n} days', array(3));
            $data[345600]   = Zurmo::t('WorkflowsModule', 'for {n} day|{n} days', array(4));
            $data[432000]   = Zurmo::t('WorkflowsModule', 'for {n} day|{n} days', array(5));
            $data[864000]   = Zurmo::t('WorkflowsModule', 'for {n} day|{n} days', array(10));
            $data[604800]   = Zurmo::t('WorkflowsModule', 'for {n} week|{n} weeks', array(1));
            $data[1209600]  = Zurmo::t('WorkflowsModule', 'for {n} week|{n} weeks', array(2));
            $data[1814400]  = Zurmo::t('WorkflowsModule', 'for {n} week|{n} weeks', array(3));
            $data[2592000]  = Zurmo::t('WorkflowsModule', 'for {n} day|{n} days', array(30));
            $data[5184000]  = Zurmo::t('WorkflowsModule', 'for {n} day|{n} days', array(60));
            $data[7776000]  = Zurmo::t('WorkflowsModule', 'for {n} day|{n} days', array(90));
            $data[10368000] = Zurmo::t('WorkflowsModule', 'for {n} day|{n} days', array(120));
            $data[12960000] = Zurmo::t('WorkflowsModule', 'for {n} day|{n} days', array(150));
            $data[15552000] = Zurmo::t('WorkflowsModule', 'for {n} day|{n} days', array(180));
            $data[31104000] = Zurmo::t('WorkflowsModule', 'for {n} year|{n} years', array(1));
        }

        public static function resolveNegativeDurationAsDistanceFromPointData(& $data, $includeHours)
        {
            assert('is_bool($includeHours)');
            $data[-31104000] = Zurmo::t('WorkflowsModule', '{n} year ago|{n} years ago', array(1));
            $data[-15552000] = Zurmo::t('WorkflowsModule', '{n} day ago|{n} days ago', array(180));
            $data[-12960000] = Zurmo::t('WorkflowsModule', '{n} day ago|{n} days ago', array(150));
            $data[-10368000] = Zurmo::t('WorkflowsModule', '{n} day ago|{n} days ago', array(120));
            $data[-7776000]  = Zurmo::t('WorkflowsModule', '{n} day ago|{n} days ago', array(90));
            $data[-5184000]  = Zurmo::t('WorkflowsModule', '{n} day ago|{n} days ago', array(60));
            $data[-2592000]  = Zurmo::t('WorkflowsModule', '{n} day ago|{n} days ago', array(30));
            $data[-1814400]  = Zurmo::t('WorkflowsModule', '{n} week ago|{n} weeks ago', array(3));
            $data[-1209600]  = Zurmo::t('WorkflowsModule', '{n} week ago|{n} weeks ago', array(2));
            $data[-604800]   = Zurmo::t('WorkflowsModule', '{n} week ago|{n} weeks ago', array(1));
            $data[-864000]   = Zurmo::t('WorkflowsModule', '{n} day ago|{n} days ago', array(10));
            $data[-432000]   = Zurmo::t('WorkflowsModule', '{n} day ago|{n} days ago', array(5));
            $data[-345600]   = Zurmo::t('WorkflowsModule', '{n} day ago|{n} days ago', array(4));
            $data[-259200]   = Zurmo::t('WorkflowsModule', '{n} day ago|{n} days ago', array(3));
            $data[-172800]   = Zurmo::t('WorkflowsModule', '{n} day ago|{n} days ago', array(2));
            $data[-86400]    = Zurmo::t('WorkflowsModule', '{n} day ago|{n} days ago', array(1));
            if($includeHours)
            {
                $data[-43200] = Zurmo::t('WorkflowsModule', '{n} hour ago|{n} hours ago', array(12));
                $data[-28800] = Zurmo::t('WorkflowsModule', '{n} hour ago|{n} hours ago', array(8));
                $data[-14400] = Zurmo::t('WorkflowsModule', '{n} hour ago|{n} hours ago', array(4));
            }
        }

        /**
         * Utilized by Email Alert to allow user to decide when an email alert should go out.
         * @param $data
         */
        public static function resolveSendAfterDurationData(& $data)
        {
            $data[0]        = Zurmo::t('WorkflowsModule', 'Immediately after workflow runs', array(5));
            $data[300]      = Zurmo::t('WorkflowsModule', '{n} minute after workflow runs|{n} minutes after workflow runs', array(5));
            $data[14400]    = Zurmo::t('WorkflowsModule', '{n} hour after workflow runs|{n} hours after workflow runs', array(4));
            $data[28800]    = Zurmo::t('WorkflowsModule', '{n} hour after workflow runs|{n} hours after workflow runs', array(8));
            $data[43200]    = Zurmo::t('WorkflowsModule', '{n} hour after workflow runs|{n} hours after workflow runs', array(12));
            $data[86400]    = Zurmo::t('WorkflowsModule', '{n} day after workflow runs|{n} days after workflow runs', array(1));
            $data[172800]   = Zurmo::t('WorkflowsModule', '{n} day after workflow runs|{n} days after workflow runs', array(2));
            $data[259200]   = Zurmo::t('WorkflowsModule', '{n} day after workflow runs|{n} days after workflow runs', array(3));
            $data[345600]   = Zurmo::t('WorkflowsModule', '{n} day after workflow runs|{n} days after workflow runs', array(4));
            $data[432000]   = Zurmo::t('WorkflowsModule', '{n} day after workflow runs|{n} days after workflow runs', array(5));
            $data[864000]   = Zurmo::t('WorkflowsModule', '{n} day after workflow runs|{n} days after workflow runs', array(10));
            $data[604800]   = Zurmo::t('WorkflowsModule', '{n} week after workflow runs|{n} weeks after workflow runs', array(1));
            $data[1209600]  = Zurmo::t('WorkflowsModule', '{n} week after workflow runs|{n} weeks after workflow runs', array(2));
            $data[1814400]  = Zurmo::t('WorkflowsModule', '{n} week after workflow runs|{n} weeks after workflow runs', array(3));
            $data[2592000]  = Zurmo::t('WorkflowsModule', '{n} day after workflow runs|{n} days after workflow runs', array(30));
            $data[5184000]  = Zurmo::t('WorkflowsModule', '{n} day after workflow runs|{n} days after workflow runs', array(60));
            $data[7776000]  = Zurmo::t('WorkflowsModule', '{n} day after workflow runs|{n} days after workflow runs', array(90));
            $data[10368000] = Zurmo::t('WorkflowsModule', '{n} day after workflow runs|{n} days after workflow runs', array(120));
            $data[12960000] = Zurmo::t('WorkflowsModule', '{n} day after workflow runs|{n} days after workflow runs', array(150));
            $data[15552000] = Zurmo::t('WorkflowsModule', '{n} day after workflow runs|{n} days after workflow runs', array(180));
            $data[31104000] = Zurmo::t('WorkflowsModule', '{n} year after workflow runs|{n} years after workflow runs', array(1));
        }

        public static function getModelsFilteredByInferredModel($modelClassName, $inferredRelationName, $inferredModelItemId)
        {
            assert('is_string($type)');
            $searchAttributeData = array();
            $searchAttributeData['clauses'] = array(
                1 => array(
                    'attributeName'        => $inferredRelationName,
                    'relatedAttributeName' => 'id',
                    'operatorType'         => 'equals',
                    'value'                => $inferredModelItemId,
                ),
            );
            $searchAttributeData['structure'] = '1';
            $joinTablesAdapter = new RedBeanModelJoinTablesQueryAdapter($modelClassName);
            $where = RedBeanModelDataProvider::makeWhere($modelClassName, $searchAttributeData, $joinTablesAdapter);
            return $modelClassName::getSubset($joinTablesAdapter, null, null, $where, null);
        }

        public static function getInferredModelsByAtrributeAndModel($relation, $model)
        {
            assert('is_string($relation)');
            $realAttributeName      = ModelRelationsAndAttributesToWorkflowAdapter::
                                      resolveRealAttributeName($relation);
            $relationModelClassName = ModelRelationsAndAttributesToWorkflowAdapter::
                                      getInferredRelationModelClassName($relation);
            $relatedModels          = array();
            foreach($model->{$realAttributeName} as $item)
            {
                try
                {
                    $modelDerivationPathToItem = RuntimeUtil::getModelDerivationPathToItem($relationModelClassName);
                    $relatedModels[]           = $item->castDown(array($modelDerivationPathToItem));
                    break;
                }
                catch (NotFoundException $e)
                {
                }
            }
            return $relatedModels;
        }

        public static function resolveDerivedModels(RedBeanModel $model, $relation)
        {
            assert('is_string($relation)');
            $modelClassName       = $model->getDerivedRelationModelClassName($relation);
            $inferredRelationName = $model->getDerivedRelationViaCastedUpModelOpposingRelationName($relation);
            return                  WorkflowUtil::getModelsFilteredByInferredModel($modelClassName, $inferredRelationName,
                $model->getClassId('Item'));
        }
    }
?>