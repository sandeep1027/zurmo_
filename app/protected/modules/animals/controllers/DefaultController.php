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

    class AnimalsDefaultController extends ZurmoModuleController
    {
        public function filters()
        {
            $modelClassName   = $this->getModule()->getPrimaryModelName();
            $viewClassName    = $modelClassName . 'EditAndDetailsView';
            return array_merge(parent::filters(),
                array(
                    array(
                        ZurmoBaseController::REQUIRED_ATTRIBUTES_FILTER_PATH . ' + create, edit',
                        'moduleClassName' => get_class($this->getModule()),
                        'viewClassName'   => $viewClassName,
                   ),
               )
            );
        }

        public function actionList()
        {
            $pageSize = Yii::app()->pagination->resolveActiveForCurrentUserByType(
                            'listPageSize', get_class($this->getModule()));
            $animal = new Animal(false);
            $searchForm = new AnimalsSearchForm($animal);
            $dataProvider = $this->makeSearchFilterListDataProvider(
                $searchForm,
                'Animal',
                'AnimalsFilteredList',
                $pageSize,
                Yii::app()->user->userModel->id
            );
            $searchFilterListView = $this->makeSearchFilterListView(
                $searchForm,
                'AnimalsFilteredList',
                $pageSize,
                AnimalsModule::getModuleLabelByTypeAndLanguage('Plural'),
                Yii::app()->user->userModel->id,
                $dataProvider
            );
            $view = new AnimalsPageView($this, $searchFilterListView);
            echo $view->render();
        }

        public function actionDetails($id)
        {
            $animal = Animal::getById(intval($id));
            ControllerSecurityUtil::resolveAccessCanCurrentUserReadModel($animal);
            AuditEvent::logAuditEvent('ZurmoModule', ZurmoModule::AUDIT_EVENT_ITEM_VIEWED, strval($animal), $animal);
            $view = new AnimalsPageView($this,
                $this->makeTitleBarAndEditAndDetailsView($animal, 'Details'));
            echo $view->render();
        }

        public function actionCreate()
        {
            $this->actionCreateByModel(new Animal());
        }

        protected function actionCreateByModel(Animal $animal, $redirectUrl = null)
        {
            $titleBarAndEditView = $this->makeTitleBarAndEditAndDetailsView(
                                            $this->attemptToSaveModelFromPost($animal, $redirectUrl), 'Edit');
            $view = new AnimalsPageView($this, $titleBarAndEditView);
            echo $view->render();
        }

        public function actionEdit($id, $redirectUrl = null)
        {
            $animal = Animal::getById(intval($id));
            $view = new AnimalsPageView($this,
                $this->makeTitleBarAndEditAndDetailsView(
                    $this->attemptToSaveModelFromPost($animal, $redirectUrl), 'Edit'));
            echo $view->render();
        }

        public function actionModalList()
        {
            $modalListLinkProvider = new SelectFromRelatedEditModalListLinkProvider(
                                            $_GET['modalTransferInformation']['sourceIdFieldId'],
                                            $_GET['modalTransferInformation']['sourceNameFieldId']
            );
            echo ModalSearchListControllerUtil::setAjaxModeAndRenderModalSearchList($this, $modalListLinkProvider,
                                                Yii::t('Default', 'AnimalsModuleSingularLabel Search',
                                                LabelUtil::getTranslationParamsForAllModules()));
        }

        public function actionDelete($id)
        {
            $animal = Animal::GetById(intval($id));
            $animal->delete();
            $this->redirect(array($this->getId() . '/index'));
        }

        /**
         * Override to provide an animal specific label for the modal page title.
         * @see ZurmoModuleController::actionSelectFromRelatedList()
         */
        public function actionSelectFromRelatedList($portletId,
                                                    $uniqueLayoutId,
                                                    $relationAttributeName,
                                                    $relationModelId,
                                                    $relationModuleId,
                                                    $pageTitle = null,
                                                    $stateMetadataAdapterClassName = null)
        {
            $pageTitle = Yii::t('Default',
                                'AnimalsModuleSingularLabel Search',
                                 LabelUtil::getTranslationParamsForAllModules());
            parent::actionSelectFromRelatedList($portletId,
                                                    $uniqueLayoutId,
                                                    $relationAttributeName,
                                                    $relationModelId,
                                                    $relationModuleId,
                                                    $pageTitle);
        }
    }
?>
