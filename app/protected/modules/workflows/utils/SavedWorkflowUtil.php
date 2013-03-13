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
     * Helper class for working with SavedWorkflow objects
     */
    class SavedWorkflowUtil
    {
        /**
         * Given an array of moduleClassNames, construct the searchAttributeData
         * @param $searchAttributeData
         * @param $moduleClassNames
         * @return array
         */
        public static function resolveSearchAttributeDataByModuleClassNames($searchAttributeData, $moduleClassNames)
        {
            assert('is_array($searchAttributeData)');
            assert('is_array($moduleClassNames)');
            $clausesCount = count($searchAttributeData['clauses']);
            $clauseStructure = null;

            if(count($moduleClassNames) == 0)
            {
                $searchAttributeData['clauses'][$clausesCount + 1] = array(
                    'attributeName'        => 'moduleClassName',
                    'operatorType'         => 'isNull',
                    'value'                => null,
                );
                $clauseStructure =  ($clausesCount + 1);
            }
            else
            {
                foreach ($moduleClassNames as $moduleClassName)
                {
                    $searchAttributeData['clauses'][$clausesCount + 1] = array(
                        'attributeName'        => 'moduleClassName',
                        'operatorType'         => 'equals',
                        'value'                => $moduleClassName
                    );
                    if ($clauseStructure != null)
                    {
                        $clauseStructure .= ' or ';
                    }
                    $clauseStructure .=  ($clausesCount + 1);
                    $clausesCount ++;
                }
            }

            if ($searchAttributeData['structure'] != null)
            {
                $searchAttributeData['structure'] .= ' and ';
            }
            $searchAttributeData['structure'] .=  "(" . $clauseStructure . ")";
            return $searchAttributeData;
        }
    }

?>