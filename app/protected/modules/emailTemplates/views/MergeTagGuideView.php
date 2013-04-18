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

    class MergeTagGuideView extends View
    {
        // TODO: @Shoaibi/@Jason: Critical: This has to be translated.
        // TODO: @Shoaibi/@Amit: Critical: Styling
        protected function renderContent()
        {
            $syntaxtContent     = $this->renderSyntaxContent();
            $examplesContent    = $this->renderExamplesContent();
            $content            = "MergeTags are a quick way to introduce reader-specific dynamic information into emails.";
            $content            .= $syntaxtContent;
            $content            .= $examplesContent;
            $content            = ZurmoHtml::tag('div', array('id' => 'mergetag-guide-modal-content',
                                                                'class' => 'mergetag-guide-modal'),
                                                        $content);
            return $content;
        }

        protected function renderSyntaxContent()
        {
            $content        = ZurmoHtml::tag('h4', array(), 'Syntax');
            $content        = ZurmoHtml::tag('div', array('id' => 'mergetag-syntax-head'), $content);
            $syntaxContent  = null;
            $syntaxItems    = array();
            $syntaxItems[]  = "A MergeTag starts with: " . MergeTagsUtil::TAG_PREFIX . " and ends with " .
                                MergeTagsUtil::TAG_SUFFIX . ".";
            $syntaxItems[]  = "Between starting and closing tag it can have property names. These names are written in " .
                                "all caps regardless of actual property name case.";
            $syntaxItems[]  = "Properties that contain more than one word are named using camel case in the system and" .
                                " to address that in MergeTag prefix" . MergeTagsUtil::CAPITAL_DELIMITER . " character" .
                                "before the letter that should be capitalize when converted";
            $syntaxItems[]  = "To access an indirect property(through a relation) of the model bound to current " .
                                "template prefix " . MergeTagsUtil::PROPERTY_DELIMITER . " property name.";
            $syntaxItems[]  = "To access previous value of a property(only supported in workflow type templates) prefix ".
                                "property name with: WAS" . MergeTagsUtil::TIME_DELIMITER.". If there is no previous" .
                                " value, current value will be used. If the attached Model does not support storing" .
                                " previous values an error will be thrown when saving template.";

            foreach($syntaxItems as $syntaxItem)
            {
                $syntaxContent .= ZurmoHtml::tag('li', array(), $syntaxItem);
            }
            $syntaxContent  = ZurmoHtml::tag('ul', array(), $syntaxContent);
            $syntaxContent  = ZurmoHtml::tag('div', array('id' => 'mergetag-syntax-body'), $syntaxContent);
            $content        .= $syntaxContent;
            $content        = ZurmoHtml::tag('div', array('id' => 'mergetag-syntax'), $content);
            return $content;
        }

        protected function renderExamplesContent()
        {
            $content            = ZurmoHtml::tag('h4', array(), 'Examples');
            $content            = ZurmoHtml::tag('div', array('id' => 'mergetag-examples-head'), $content);
            $examplesContent    = null;
            $exampleItems       = array();
            $exampleItems[]     = "Adding a contact's First Name(firstName): " .
                                    MergeTagsUtil::TAG_PREFIX . "FIRST". MergeTagsUtil::CAPITAL_DELIMITER .
                                    "NAME" . MergeTagsUtil::TAG_SUFFIX;
            $exampleItems[]     = "Adding a contact's city(primaryAddress->city): " . MergeTagsUtil::TAG_PREFIX .
                                    "PRIMARY" . MergeTagsUtil::CAPITAL_DELIMITER . "ADDRESS" .
                                    MergeTagsUtil::PROPERTY_DELIMITER . "CITY" . MergeTagsUtil::TAG_SUFFIX;
            $exampleItems[]     = "Adding a user's previous primary email address: " .
                                    MergeTagsUtil::TAG_PREFIX . "WAS" . MergeTagsUtil::TIME_DELIMITER . "PRIMARY" .
                                    MergeTagsUtil::CAPITAL_DELIMITER . "EMAIL" . MergeTagsUtil::PROPERTY_DELIMITER .
                                    "EMAIL" . MergeTagsUtil::CAPITAL_DELIMITER . "ADDRESS" . MergeTagsUtil::TAG_SUFFIX;
            foreach($exampleItems as $exampleItem)
            {
                $examplesContent .= ZurmoHtml::tag('li', array(), $exampleItem);
            }
            $examplesContent    = ZurmoHtml::tag('ul', array(), $examplesContent);
            $examplesContent    = ZurmoHtml::tag('div', array('id' => 'mergetag-examples-body'), $examplesContent);
            $content            .= $examplesContent;
            $content            = ZurmoHtml::tag('div', array('id' => 'mergetag-examples'), $content);
            return $content;
        }
    }
?>