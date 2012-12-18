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

            $dataProvider = $this->makeSearchDataProvider(
                $searchForm,
                'Animal',
                $pageSize,
                null,
                'AnimalsSearchView'
            );

            $actionBarSearchAndListView = $this->makeActionBarSearchAndListView(
                $searchForm,
                $pageSize,
                AnimalsModule::getModuleLabelByTypeAndLanguage('Plural'),
                Yii::app()->user->userModel->id,
                $dataProvider
            );

            $view = new AnimalsPageView(ZurmoDefaultViewUtil::
                                         makeStandardViewForCurrentUser($this, $actionBarSearchAndListView));
            echo $view->render();
        }

        public function actionDetails($id)
        {
            $animal = static::getModelAndCatchNotFoundAndDisplayError('Animal', intval($id));
            ControllerSecurityUtil::resolveAccessCanCurrentUserReadModel($animal);
            AuditEvent::logAuditEvent('ZurmoModule', ZurmoModule::AUDIT_EVENT_ITEM_VIEWED, array(strval($animal), 'AnimalsModule'), $animal);

            $titleBarAndEditView = $this->makeEditAndDetailsView($animal, 'Details');
            $view = new AnimalsPageView(ZurmoDefaultViewUtil::
                                         makeStandardViewForCurrentUser($this, $titleBarAndEditView));
            echo $view->render();
        }

        public function actionCreate()
        {
            $this->actionCreateByModel(new Animal());
        }

        protected function actionCreateByModel(Animal $animal, $redirectUrl = null)
        {
            $titleBarAndEditView = $this->makeEditAndDetailsView(
                                            $this->attemptToSaveModelFromPost($animal, $redirectUrl), 'Edit');
            $view = new AnimalsPageView(ZurmoDefaultViewUtil::
                                         makeStandardViewForCurrentUser($this, $titleBarAndEditView));
            echo $view->render();
        }

        public function actionEdit($id, $redirectUrl = null)
        {
            $animal = Animal::getById(intval($id));
            $titleBarAndEditView = $this->makeEditAndDetailsView(
                                            $this->attemptToSaveModelFromPost($animal, $redirectUrl), 'Edit');
            $view = new AnimalsPageView(ZurmoDefaultViewUtil::
                                         makeStandardViewForCurrentUser($this, $titleBarAndEditView));
            echo $view->render();
        }

        /**
         * Action for displaying a mass edit form and also action when that form is first submitted.
         * When the form is submitted, in the event that the quantity of models to update is greater
         * than the pageSize, then once the pageSize quantity has been reached, the user will be
         * redirected to the makeMassEditProgressView.
         * In the mass edit progress view, a javascript refresh will take place that will call a refresh
         * action, usually massEditProgressSave.
         * If there is no need for a progress view, then a flash message will be added and the user will
         * be redirected to the list view for the model.  A flash message will appear providing information
         * on the updated records.
         * @see Controler->makeMassEditProgressView
         * @see Controller->processMassEdit
         * @see
         */
        public function actionMassEdit()
        {
            $pageSize = Yii::app()->pagination->resolveActiveForCurrentUserByType(
                            'massEditProgressPageSize');
            $animal = new Animal(false);
            $activeAttributes = $this->resolveActiveAttributesFromMassEditPost();
            $dataProvider = $this->getDataProviderByResolvingSelectAllFromGet(
                new AnimalsSearchForm($animal),
                'Animal',
                $pageSize,
                Yii::app()->user->userModel->id,
                'AnimalsFilteredList');
            $selectedRecordCount = $this->getSelectedRecordCountByResolvingSelectAllFromGet($dataProvider);
            $account = $this->processMassEdit(
                $pageSize,
                $activeAttributes,
                $selectedRecordCount,
                'AnimalsPageView',
                $animal,
                AnimalsModule::getModuleLabelByTypeAndLanguage('Plural'),
                $dataProvider
            );
            $massEditView = $this->makeMassEditView(
                $animal,
                $activeAttributes,
                $selectedRecordCount,
                AccountsModule::getModuleLabelByTypeAndLanguage('Plural')
            );
            $view = new AnimalsPageView(ZurmoDefaultViewUtil::
                                         makeStandardViewForCurrentUser($this, $massEditView));
            echo $view->render();
        }

        /**
         * Action called in the event that the mass edit quantity is larger than the pageSize.
         * This action is called after the pageSize quantity has been updated and continues to be
         * called until the mass edit action is complete.  For example, if there are 20 records to update
         * and the pageSize is 5, then this action will be called 3 times.  The first 5 are updated when
         * the actionMassEdit is called upon the initial form submission.
         */
        public function actionMassEditProgressSave()
        {
            $pageSize = Yii::app()->pagination->resolveActiveForCurrentUserByType(
                            'massEditProgressPageSize');
            $animal = new Animal(false);
            $dataProvider = $this->getDataProviderByResolvingSelectAllFromGet(
                new AccountsSearchForm($account),
                'Animal',
                $pageSize,
                Yii::app()->user->userModel->id,
                'AnimalsFilteredList'
            );
            $this->processMassEditProgressSave(
                'Animal',
                $pageSize,
                AccountsModule::getModuleLabelByTypeAndLanguage('Plural'),
                $dataProvider
            );
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

        public function actionExport()
        {
            $this->export();
        }
    }
?>
