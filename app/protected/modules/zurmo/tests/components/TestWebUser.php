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

    class TestWebUser extends WebUser
    {
        public function __set($attributeName, $value)
        {
            if ($attributeName == 'userModel')
            {
                $this->userModel = $value;
            }
            else
            {
                parent::__set($attributeName, $value);
            }
        }

    /**
     * Override for testing framework.  The regenerateId call was causing issues with a walkthrough test that logged out
     * and logged back in.  It would complain about headers already being sent. Under normal usage through the user
     * interface this wouldn't be an issue so this is removed for testing.
     * @see CWebUser::changeIdentity()
     */
    protected function changeIdentity($id, $name, $states)
    {
        //Yii::app()->getSession()->regenerateID();  //This was causing problems for tests.
        $this->setId($id);
        $this->setName($name);
        $this->loadIdentityStates($states);
    }
    }
?>
