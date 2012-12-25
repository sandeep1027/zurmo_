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
     * Base class used for wrapping a view of runtime filters
     */
    class RuntimeFiltersForPortletView extends ReportResultsComponentForPortletView
    {
        public function getTitle()
        {
            $title  = Yii::t('Default', 'RunTimeFiltersTitleNeeded');
            return $title;
        }

        public function renderContent()
        {
            return $this->renderForm();
        }

        public static function getFormId()
        {
            return 'edit-form';
        }

        protected function renderForm()
        {
            $content  = $this->renderRefreshLink();
            $content .= '<div class="wrapper">';
            $content .= '<div class="wide form">';
            $clipWidget = new ClipWidget();
            list($form, $formStart) = $clipWidget->renderBeginWidget(
                                                            'ReportActiveForm',
                                                            array('id'                      => static::getFormId(),
                                                                  'action'                  => $this->getFormActionUrl(),
                                                                  'enableAjaxValidation'    => true,
                                                                  'clientOptions'           => $this->getClientOptions(),
                                                                  'modelClassNameForError'  => $this->getWizardFormClassName())
                                                            );
            $content       .= $formStart;
            $rowCount       = 0;
            $items          = $this->renderItems($rowCount, $form);
            $itemsContent   = $this->getNonSortableListContent($items);
            $content       .= ZurmoHtml::tag('div', array('class' => 'attribute-rows'), $itemsContent);
            $content       .= $this->renderViewToolBarContainer($form);
            $formEnd        = $clipWidget->renderEndWidget();
            $content       .= $formEnd;
            $content       .= '</div></div>';
            return $content;
        }

        protected function getWizardFormClassName()
        {
            return ReportToWizardFormAdapter::getFormClassNameByType($this->params['relationModel']->getType());
        }

        protected function renderItems(& $rowCount, ReportActiveForm $form)
        {
            assert('is_int($rowCount)');
            $items                      = array();
            $wizardFormClassName        = $this->getWizardFormClassName();
            $treeType                   = ComponentForReportForm::TYPE_FILTERS;
            $trackableStructurePosition = false; //can we set this to false without jacking things up?
            foreach($this->params["relationModel"]->getFilters() as $filterForReportForm)
            {
                if($filterForReportForm->availableAtRunTime)
                {
                    $nodeIdWithoutTreeType      = $filterForReportForm->attributeIndexOrDerivedType;
                    $inputPrefixData            = ReportRelationsAndAttributesToTreeAdapter::
                                                  resolveInputPrefixData($nodeIdWithoutTreeType, $wizardFormClassName,
                                                  $treeType, $rowCount);
                    $adapter                    = new RuntimeReportAttributeToElementAdapter($inputPrefixData, $filterForReportForm,
                                                  $form, $treeType);
                    $view                       = new AttributeRowForReportComponentView($adapter,
                                                  $rowCount, $inputPrefixData,
                                                  ReportRelationsAndAttributesToTreeAdapter::
                                                  resolveAttributeByNodeId($nodeIdWithoutTreeType),
                                                  (bool)$trackableStructurePosition, false);
                    $view->addWrapper           = false;
                    $items[]                    = array('content' => $view->render());
                }
                $rowCount ++;
            }
            return $items;
        }

        protected function getNonSortableListContent(Array $items)
        {
            $content = null;
            foreach($items as $item)
            {
                $content .= ZurmoHtml::tag('li', array(), $item['content']);
            }
            return ZurmoHtml::tag('ul', array(), $content);
        }

        protected function getClientOptions()
        {
            return array(
                        'validateOnSubmit'  => true,
                        'validateOnChange'  => false,
                        'beforeValidate'    => 'js:beforeValidateAction',
                        'afterValidate'     => 'js:afterValidateAjaxAction',
                        'afterValidateAjax' => $this->renderConfigSaveAjax(static::getFormId()),
                    );
        }

        protected function getFormActionUrl()
        {
            return Yii::app()->createUrl('reports/default/applyRuntimeFilters',
                                         array('id' => $this->params["relationModel"]->getId()));
        }

        protected function renderConfigSaveAjax($formName)
        {
            return     "$('#apply-runtime-filters').removeClass('loading');
                        $('#apply-runtime-filters').removeClass('loading-ajax-submit');
                        $('#ReportResultsGridForPortletView').find('.refreshPortletLink').click();
                        $('#ReportChartForPortletView').find('.refreshPortletLink').click();
                       ";
        }

        protected function renderViewToolBarContainer($form)
        {
            $content  = '<div class="view-toolbar-container clearfix">';
            $content .= '<div class="form-toolbar">';
            $content .= $this->renderViewToolBarLinks($form);
            $content .= '</div></div>';
            return $content;
        }

        protected function renderViewToolBarLinks($form)
        {
            $params                = array();
            $params['label']       = Yii::t('Default', 'Apply');
            $params['htmlOptions'] = array('id'      => 'apply-runtime-filters',
                                           'onclick' => 'js:$(this).addClass("attachLoadingTarget");');
            $applyElement          = new SaveButtonActionElement(null, null, null, $params);
            $resetElement          = new RefreshRuntimeFiltersAjaxLinkActionElement(null, null,
                                         $this->params['relationModel']->getId(), array());
            return $resetElement->render() . $applyElement->render();
        }
    }
?>