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
     * Class utilized by 'select' modal popup in the edit view
     */
    class ProductTemplateSelectForPortletFromRelatedEditModalListLinkProvider extends ModalListLinkProvider
    {
	/**
         * Id of input field in display for saving back a selected
         * record from the modal list view.
         * @see $sourceIdFieldId
         */
        protected $sourceIdFieldId;

        /**
         * Name of input field in display for saving back a selected
         * record from the modal list view.
         * @see $sourceNameFieldId
         */
        protected $sourceNameFieldId;

	/**
         * sourceIdFieldName and sourceNameFieldId are needed to know
         * which fields in the parent form to populate data with
         * upon selecting a row in the listview
         *
         */
        public function __construct($sourceIdFieldId, $sourceNameFieldId, $relationAttributeName, $relationModelId, $relationModuleId,
                                    $uniqueLayoutId, $portletId, $moduleId)
        {
            assert('is_string($sourceIdFieldId)');
            assert('is_string($sourceNameFieldId)');
	    assert('is_string($relationAttributeName)');
            assert('is_int($relationModelId)');
            assert('is_string($relationModuleId)');
            assert('is_string($uniqueLayoutId)');
            assert('is_int($portletId)');
            assert('is_string($moduleId)');
	    $this->sourceIdFieldId	  = $sourceIdFieldId;
            $this->sourceNameFieldId	  = $sourceNameFieldId;
            $this->relationAttributeName  = $relationAttributeName;
            $this->relationModelId        = $relationModelId;
            $this->relationModuleId       = $relationModuleId;
            $this->uniqueLayoutId         = $uniqueLayoutId;
            $this->portletId              = $portletId;
            $this->moduleId               = $moduleId;
        }

        public function getLinkString($attributeString)
        {
	    $url = Yii::app()->createUrl("products/default/createProductFromProductTemplate", array('relationModuleId' => $this->relationModuleId,
												    'portletId' => $this->portletId,
												    'uniqueLayoutId' => $this->uniqueLayoutId));
            $errorInProcess = CJavaScript::quote(Zurmo::t('Core', 'There was an error processing your request'));
	    $string  = 'ZurmoHtml::link(';
            $string .= $attributeString . ', ';
            $string .= '"javascript:addProductRowToPortletGridView(\'$data->id\', \'' . $url . '\', \'' . $this->relationAttributeName . '\', \'' . $this->relationModelId . '\'
			    , \'' . $this->uniqueLayoutId . '\', \'' . $errorInProcess . '\')"';
            $string .= ')';
            return $string;
        }
    }
?>