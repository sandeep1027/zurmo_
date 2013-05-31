<?php
    /*********************************************************************************
     * Zurmo is a customer relationship management program developed by
     * Zurmo, Inc. Copyright (C) 2013 Zurmo Inc.
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
     * You can contact Zurmo, Inc. with a mailing address at 27 North Wacker Drive
     * Suite 370 Chicago, IL 60606. or at email address contact@zurmo.com.
     *
     * The interactive user interfaces in original and modified versions
     * of this program must display Appropriate Legal Notices, as required under
     * Section 5 of the GNU General Public License version 3.
     *
     * In accordance with Section 7(b) of the GNU General Public License version 3,
     * these Appropriate Legal Notices must retain the display of the Zurmo
     * logo and Zurmo copyright notice. If the display of the logo is not reasonably
     * feasible for technical reasons, the Appropriate Legal Notices must display the words
     * "Copyright Zurmo Inc. 2013. All rights reserved".
     ********************************************************************************/

    /**
     * Base class used for displaying the performance metrics for marketing, campaigns, and marketing list related dashboards
     */
    abstract class MarketingMetricsView extends ConfigurableMetadataView implements PortletViewInterface
    {
        /**
         * Portlet parameters passed in from the portlet.
         * @var array
         */
        protected $params;

        protected $controllerId;

        protected $moduleId;

        protected $model;

        protected $uniqueLayoutId;

        protected $viewData;

        protected $formModel;

        protected $formModelClassName;

        abstract public function getConfigurationView();

        /**
         * Override in children class and return form model class name
         * @throws NotImplementedException
         */
        public static function getModuleClassName()
        {
            throw new NotImplementedException();
        }

        public static function canUserConfigure()
        {
            return true;
        }

        public static function canUserRemove()
        {
            return false;
        }

        /**
         * What kind of PortletRules this view follows
         * @return PortletRulesType as string.
         */
        public static function getPortletRulesType()
        {
            return 'ModelDetails';
        }

        public function renderPortletHeadContent()
        {
            return null;
        }

        public static function getDefaultMetadata()
        {
            $metadata = array(
                'perUser' => array(
                    'beginDate' => "eval:DateTimeCalculatorUtil::calculateNewByDaysFromNow(-30,
                                    new DateTime(null, new DateTimeZone(Yii::app()->timeZoneHelper->getForCurrentUser())));",
                    'endDate'   => "eval:DateTimeCalculatorUtil::calculateNewByDaysFromNow(0,
                                    new DateTime(null, new DateTimeZone(Yii::app()->timeZoneHelper->getForCurrentUser())));",
                    'groupBy'   => MarketingOverallMetricsForm::GROUPING_TYPE_WEEK,
                ),
            );
            return $metadata;
        }

        /**
         * Some extra assertions are made to ensure this view is used in a way that it supports.
         */
        public function __construct($viewData, $params, $uniqueLayoutId)
        {
            assert('is_array($viewData) || $viewData == null');
            assert('isset($params["portletId"])');
            assert('is_string($uniqueLayoutId)');
            $this->moduleId       = 'home';
            $this->viewData       = $viewData;
            $this->params         = $params;
            $this->uniqueLayoutId = $uniqueLayoutId;
        }

        public function renderContent()
        {
            $content  = ZurmoHtml::tag('h3', array(), Zurmo::t('MarketingModule', 'What is going on with Marketing?'));
            $content .= $this->renderConfigureElementsContent();
            $content .= $this->renderMetricsWrapperContent();
            return $content;
        }

        protected function renderConfigureElementsContent()
        {
            $dateRangeContent  = DateTimeUtil::resolveValueForDateLocaleFormattedDisplay($this->resolveForm()->beginDate)
                                 . ' - ' .
                                 DateTimeUtil::resolveValueForDateLocaleFormattedDisplay($this->resolveForm()->endDate);
            $content           = ZurmoHtml::tag('div', array(), $dateRangeContent);
            $content          .= $this->renderGroupByConfigurationForm();
            return $content;
        }

        protected function renderMetricsWrapperContent()
        {
            $cssClass = 'third marketing-graph';
            $content  = ZurmoHtml::tag('div', array('class' => $cssClass), $this->renderOverallListPerformanceContent());
            $content .= ZurmoHtml::tag('div', array('class' => $cssClass), $this->renderEmailsInThisListContent());
            $content .= ZurmoHtml::tag('div', array('class' => $cssClass), $this->renderListGrowthContent());
            return ZurmoHtml::tag('div', array('class' => 'graph-container clearfix'), $content);
        }

        protected function renderOverallListPerformanceContent()
        {
            $chartDataProvider  = $this->resolveChartDataProvider('MarketingListPerformance');
            $content  = ZurmoHtml::tag('h3', array(), Zurmo::t('MarketingModule', 'Overall List Performance'));
            $content .= MarketingMetricsUtil::renderOverallListPerformanceChartContent(
                        $chartDataProvider, $this->uniqueLayoutId . 'OverallListPerformance');
            return $content;
        }

        protected function renderEmailsInThisListContent()
        {
            $chartDataProvider  = $this->resolveChartDataProvider('MarketingEmailsInThisList');
            $content  = ZurmoHtml::tag('h3', array(), Zurmo::t('MarketingModule', 'Emails in this list'));
            $content .= MarketingMetricsUtil::renderEmailsInThisListChartContent(
                        $chartDataProvider, $this->uniqueLayoutId . 'EmailsInThisList');
            return $content;
        }

        protected function renderListGrowthContent()
        {
            $chartDataProvider  = $this->resolveChartDataProvider('MarketingListGrowth');
            $content  = ZurmoHtml::tag('h3', array(), Zurmo::t('MarketingModule', 'List Growth'));
            $content .= MarketingMetricsUtil::renderListGrowthChartContent(
                        $chartDataProvider, $this->uniqueLayoutId . 'ListGrowth');
            return $content;
        }

        protected function resolveChartDataProvider($type)
        {
            assert('is_string($type)');
            $chartDataProvider  = ChartDataProviderFactory::createByType($type);
            $chartDataProvider->setBeginDate($this->resolveForm()->beginDate);
            $chartDataProvider->setEndDate($this->resolveForm()->beginDate);
            $chartDataProvider->setGroupBy($this->resolveForm()->groupBy);
            return $chartDataProvider;
        }

        /**
         * After a portlet action is completed, the portlet must be refreshed. This is the url to correctly
         * refresh the portlet content.
         */
        protected function getPortletDetailsUrl()
        {
            return Yii::app()->createUrl('/' . $this->moduleId . '/defaultPortlet/details',
                array_merge($_GET, array( 'portletId' =>
                $this->params['portletId'],
                    'uniqueLayoutId' => $this->uniqueLayoutId)));
        }

        /**
         * Call to save the portlet configuration
         */
        protected function getPortletSaveConfigurationUrl()
        {
            return Yii::app()->createUrl('/' . $this->moduleId . '/defaultPortlet/modalConfigSave',
                array_merge($_GET, array( 'portletId' => $this->params['portletId'],
                            'uniqueLayoutId' => $this->uniqueLayoutId)));
        }

        /**
         * Url to go to after an action is completed. Typically returns user to either a model's detail view or
         * the home page dashboard.
         */
        protected function getNonAjaxRedirectUrl()
        {
            return Yii::app()->createUrl('/' . $this->moduleId . '/default/details',
                array( 'id' => $this->params['relationModel']->id));
        }

        protected function renderGroupByConfigurationForm()
        {
            $clipWidget = new ClipWidget();
            list($form, $formStart) = $clipWidget->renderBeginWidget(
                'ZurmoActiveForm',
                array(
                    'id' => $this->getFormId(),
                )
            );
            $content  = $formStart;
            $content .= $this->renderGroupByConfigurationFormLayout($form);
            $formEnd  = $clipWidget->renderEndWidget();
            $content .= $formEnd;
            $this->registerGroupByConfigurationFormLayoutScripts($form);
            return $content;
        }

        protected function getFormId()
        {
            return 'marketing-metrics-group-by-configuration-form-' . $this->uniqueLayoutId;
        }

        protected function renderGroupByConfigurationFormLayout($form)
        {
            assert('$form instanceof ZurmoActiveForm');
            $groupByElement = new MarketingMetricsGroupByRadioElement($this->resolveForm(), 'groupBy', $form);
            return ZurmoHtml::tag('div', array('id' => $this->getFormId() . '_groupBy_area', 'class' => 'mini-pillbox'), $groupByElement->render());
        }

        protected function registerGroupByConfigurationFormLayoutScripts($form)
        {
            assert('$form instanceof ZurmoActiveForm');
            $ajaxSubmitScript = ZurmoHtml::ajax(array(
                'type'     => 'POST',
                'data'     => 'js:$("#' . $form->getId() . '").serialize() + \'&' . get_class($this->resolveForm()) .
                              '[groupBy]=\' + $(this).data("value")',
                'url'      =>  $this->getPortletSaveConfigurationUrl(),
                //'beforeSend' => 'js:function(){makeSmallLoadingSpinner(true, "#MarketingDashboardView");
                //                $("#MarketingDashboardView").addClass("loading");}',
                'complete' => 'function(XMLHttpRequest, textStatus){juiPortlets.refresh();}',
                'update' => '#' . $this->uniqueLayoutId,

            ));
            Yii::app()->clientScript->registerScript($this->uniqueLayoutId . 'groupByChangeScript', "
            $('." . $this->getFormId() . "marketingMetricsGroupByLink').click(function()
                {
                    " . $ajaxSubmitScript . "
                }
            );
            ");
        }

        protected function resolveForm()
        {
            if($this->formModel !== null)
            {
                return $this->formModel;
            }
            if($this->formModelClassName == null)
            {
                throw new NotSupportedException();
            }
            else
            {
                $formModelClassName = $this->formModelClassName;
            }
            $formModel = new $formModelClassName();
            if ($this->viewData!='')
            {
                $formModel->setAttributes($this->viewData);
            }
            else
            {
                $metadata        = self::getMetadata();
                $perUserMetadata = $metadata['perUser'];
                $this->resolveEvaluateSubString($perUserMetadata, null);
                $formModel->setAttributes($perUserMetadata);
            }
            $this->formModel = $formModel;
            return $formModel;
        }
    }
?>