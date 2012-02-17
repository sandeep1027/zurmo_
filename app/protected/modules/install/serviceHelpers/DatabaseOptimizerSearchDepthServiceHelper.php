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
     * Check settings related to optimizer search depth global option.
     * For more details: DatabaseOptimizerSearchDepthServiceHelper
     */
    class DatabaseOptimizerSearchDepthServiceHelper extends ServiceHelper
    {
        protected $required = true;
        protected $form;

        public function __construct($form)
        {
            assert('$form instanceof InstallSettingsForm');
            $this->form = $form;
        }

        protected function checkService()
        {
            $passed = true;
            $optimizerSearchDepth = null;
            if (!InstallUtil::checkDatabaseOptimizerSearchDepthValue('mysql',
                                                               $this->form->databaseHostname,
                                                               $this->form->databaseUsername,
                                                               $this->form->databasePassword,
                                                               $optimizerSearchDepth))
            {
                if ($optimizerSearchDepth == 0)
                {
                }
                else
                {
                    $this->message  = Yii::t('Default', 'Database optimizer_search_depth value is:') . $optimizerSearchDepth . ', ';
                    $this->message .= Yii::t('Default', 'it is required to be set to 0') . '.';
                }
                $passed = false;
            }
            else
            {
                $this->message = Yii::t('Default', 'Database optimizer-search-depth size meets requirement.');
            }
            return $passed;
        }
    }
?>