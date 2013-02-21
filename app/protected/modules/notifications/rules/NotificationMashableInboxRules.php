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

    class NotificationMashableInboxRules extends MashableInboxRules
    {

        public $shouldRenderCreateAction = false;

        public function getUnreadCountForCurrentUser()
        {
            return Notification::getCountByUser(Yii::app()->user->userModel);
        }

        public function getModelClassName()
        {
            return 'Notification';
        }

        protected function getListViewClassName()
        {
            return 'NotificationsForUserListView';
        }

        public function getMachableInboxOrderByAttributeName()
        {
            return 'id';
        }

        public function getActionViewOptions()
        {
            return array();
        }

        public function getMetadataFilteredByOption($option)
        {
            return self::getSearchAttributeData();
        }

        public function getMetadataFilteredByFilteredBy($filteredBy)
        {
            return null;
        }

        public function getModelStringContent(RedBeanModel $model)
        {
            $modelDisplayString = strval($model);
            $params          = array('label' => $modelDisplayString, 'wrapLabel' => false);
            $moduleClassName = $model->getModuleClassName();
            $moduleId        = $moduleClassName::getDirectoryName();
            return $modelDisplayString;
        }

        public function getModelCreationTimeContent(RedBeanModel $model)
        {
            return null;
        }

        public function getSearchAttributeData($searchTerm = '')
        {
            $searchAttributeData['clauses'] = array(
                1 => array(
                    'attributeName'        => 'owner',
                    'relatedAttributeName' => 'id',
                    'operatorType'         => 'equals',
                    'value'                => Yii::app()->user->userModel->id,
                ),
                2 => array(
                        'attributeName'        => 'notificationMessage',
                        'relatedAttributeName' => 'htmlContent',
                        'operatorType'         => 'contains',
                        'value'                => $searchTerm,
                ),
                3 => array(
                        'attributeName'        => 'notificationMessage',
                        'relatedAttributeName' => 'textContent',
                        'operatorType'         => 'contains',
                        'value'                => $searchTerm,
                )
            );
            $searchAttributeData['structure'] = '1 and (2 or 3)';
            return $searchAttributeData;
        }

        public function resolveMarkRead($modelId)
        {
            assert('$modelId > 0');
            $this->resolveChangeHasReadLatestStatus($modelId, true);
        }

        public function resolveMarkUnread($modelId)
        {
            assert('$modelId > 0');
            $this->resolveChangeHasReadLatestStatus($modelId, false);

        }

        private function resolveChangeHasReadLatestStatus($modelId, $newStatus)
        {
            throw new NotImplementedException();
        }
    }
?>
