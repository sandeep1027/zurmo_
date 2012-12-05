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
     * Accounts Module Super User Mass Edit Walkthrough.
     * Walkthrough for the super user of all possible controller actions.
     * Since this is a super user, he should have access to all controller actions
     * without any exceptions being thrown.
     */
    class AccountsSuperUserMassEditWalkthroughTest extends ZurmoWalkthroughBaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
            $super = User::getByUsername('super');
            Yii::app()->user->userModel = $super;

            //Setup test data owned by the super user.
            AccountTestHelper::createAccountByNameForOwner('superAccount', $super);
            AccountTestHelper::createAccountByNameForOwner('superAccount2', $super);
            AccountTestHelper::createAccountByNameForOwner('superAccount3', $super);
            AccountTestHelper::createAccountByNameForOwner('superAccount4', $super);
            AccountTestHelper::createAccountByNameForOwner('superAccount5', $super);
            AccountTestHelper::createAccountByNameForOwner('superAccount6', $super);
            AccountTestHelper::createAccountByNameForOwner('superAccount7', $super);
            AccountTestHelper::createAccountByNameForOwner('superAccount8', $super);
            AccountTestHelper::createAccountByNameForOwner('superAccount9', $super);
            AccountTestHelper::createAccountByNameForOwner('superAccount10', $super);
            AccountTestHelper::createAccountByNameForOwner('superAccount11', $super);
            AccountTestHelper::createAccountByNameForOwner('superAccount12', $super);
            //Setup default dashboard.
            Dashboard::getByLayoutIdAndUser(Dashboard::DEFAULT_USER_LAYOUT_ID, $super);
        }

        public function testSuperUserAllDefaultControllerActions()
        {
            $super = $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');

            

            //Default Controller actions requiring some sort of parameter via POST or GET
            //Load Model Edit Views
            $accounts = Account::getAll();
            $this->assertEquals(12, count($accounts));
            $superAccountId = self::getModelIdByModelNameAndName ('Account', 'superAccount');
            $superAccountId2 = self::getModelIdByModelNameAndName('Account', 'superAccount2');
            $superAccountId3 = self::getModelIdByModelNameAndName('Account', 'superAccount3');
            $superAccountId4 = self::getModelIdByModelNameAndName('Account', 'superAccount4');
            $superAccountId5 = self::getModelIdByModelNameAndName ('Account', 'superAccount5');
            $superAccountId6 = self::getModelIdByModelNameAndName('Account', 'superAccount6');
            $superAccountId7 = self::getModelIdByModelNameAndName('Account', 'superAccount7');
            $superAccountId8 = self::getModelIdByModelNameAndName('Account', 'superAccount8');
            $superAccountId9 = self::getModelIdByModelNameAndName ('Account', 'superAccount9');
            $superAccountId10 = self::getModelIdByModelNameAndName('Account', 'superAccount10');
            $superAccountId11 = self::getModelIdByModelNameAndName('Account', 'superAccount11');
            $superAccountId12 = self::getModelIdByModelNameAndName('Account', 'superAccount12');
            $this->setGetArray(array('id' => $superAccountId));
            $this->runControllerWithNoExceptionsAndGetContent('accounts/default/edit');
            //Save account.
            $superAccount = Account::getById($superAccountId);
            $this->assertEquals(null, $superAccount->officePhone);
            $this->setPostArray(array('Account' => array('officePhone' => '456765421')));
            //Make sure the redirect is to the details view and not the list view.
            $this->runControllerWithRedirectExceptionAndGetContent('accounts/default/edit',
            Yii::app()->createUrl('accounts/default/details', array('id' => $superAccountId)));
            $superAccount = Account::getById($superAccountId);
            $this->assertEquals('456765421', $superAccount->officePhone);
            //Test having a failed validation on the account during save.
            $this->setGetArray (array('id'      => $superAccountId));
            $this->setPostArray(array('Account' => array('name' => '')));
            $content = $this->runControllerWithNoExceptionsAndGetContent('accounts/default/edit');
            $this->assertFalse(strpos($content, 'Name cannot be blank') === false);

            //Load Model Detail Views
            $this->setGetArray(array('id' => $superAccountId));
            $this->resetPostArray();
            $this->runControllerWithNoExceptionsAndGetContent('accounts/default/details');

            //Load Model MassEdit Views.
            //MassEdit view for single selected ids
            $this->setGetArray(array('selectedIds' => '4,5,6,7,8', 'selectAll' => ''));  // Not Coding Standard
            $this->resetPostArray();
            $content = $this->runControllerWithNoExceptionsAndGetContent('accounts/default/massEdit');
            $this->assertFalse(strpos($content, '<strong>5</strong>&#160;records selected for updating') === false);

            //MassEdit view for all result selected ids
            $this->setGetArray(array('selectAll' => '1'));
            $this->resetPostArray();
            $content = $this->runControllerWithNoExceptionsAndGetContent('accounts/default/massEdit');
            $this->assertFalse(strpos($content, '<strong>12</strong>&#160;records selected for updating') === false);

            //save Model MassEdit for selected Ids
            //Test that the 2 accounts do not have the office phone number we are populating them with.
            $account1 = Account::getById($superAccountId);
            $account2 = Account::getById($superAccountId2);
            $account3 = Account::getById($superAccountId3);
            $account4 = Account::getById($superAccountId4);
            $this->assertNotEquals('7788', $account1->officePhone);
            $this->assertNotEquals('7788', $account2->officePhone);
            $this->assertNotEquals('7788', $account3->officePhone);
            $this->assertNotEquals('7788', $account4->officePhone);
            $this->setGetArray(array(
                'selectedIds' => $superAccountId . ',' . $superAccountId2, // Not Coding Standard
                'selectAll' => '',
                'Account_page' => 1));
            $this->setPostArray(array(
                'Account'  => array('officePhone' => '7788'),
                'MassEdit' => array('officePhone' => 1)
            ));
            $this->runControllerWithRedirectExceptionAndGetContent('accounts/default/massEdit');
            //Test that the 2 accounts have the new office phone number and the other accounts do not.
            $account1 = Account::getById($superAccountId);
            $account2 = Account::getById($superAccountId2);
            $account3 = Account::getById($superAccountId3);
            $account4 = Account::getById($superAccountId4);
            $account5 = Account::getById($superAccountId5);
            $account6 = Account::getById($superAccountId6);
            $account7 = Account::getById($superAccountId7);
            $account8 = Account::getById($superAccountId8);
            $account9 = Account::getById($superAccountId9);
            $account10 = Account::getById($superAccountId10);
            $account11 = Account::getById($superAccountId11);
            $account12 = Account::getById($superAccountId12);
            $this->assertEquals   ('7788', $account1->officePhone);
            $this->assertEquals   ('7788', $account2->officePhone);
            $this->assertNotEquals('7788', $account3->officePhone);
            $this->assertNotEquals('7788', $account4->officePhone);
            $this->assertNotEquals('7788', $account5->officePhone);
            $this->assertNotEquals('7788', $account6->officePhone);
            $this->assertNotEquals('7788', $account7->officePhone);
            $this->assertNotEquals('7788', $account8->officePhone);
            $this->assertNotEquals('7788', $account9->officePhone);
            $this->assertNotEquals('7788', $account10->officePhone);
            $this->assertNotEquals('7788', $account11->officePhone);
            $this->assertNotEquals('7788', $account12->officePhone);

            //save Model MassEdit for entire search result
            $this->setGetArray(array(
                'selectAll' => '1',
                'Account_page' => 1));
            $this->setPostArray(array(
                'Account'  => array('officePhone' => '4455'),
                'MassEdit' => array('officePhone' => 1)
            ));
            $pageSize = Yii::app()->pagination->getForCurrentUserByType('massEditProgressPageSize');
            $this->assertEquals(5, $pageSize);
            Yii::app()->pagination->setForCurrentUserByType('massEditProgressPageSize', 20);
            $this->runControllerWithRedirectExceptionAndGetContent('accounts/default/massEdit');
            Yii::app()->pagination->setForCurrentUserByType('massEditProgressPageSize', $pageSize);
            //Test that all accounts have the new phone number.
            $account1 = Account::getById($superAccountId);
            $account2 = Account::getById($superAccountId2);
            $account3 = Account::getById($superAccountId3);
            $account4 = Account::getById($superAccountId4);
            $account5 = Account::getById($superAccountId5);
            $account6 = Account::getById($superAccountId6);
            $account7 = Account::getById($superAccountId7);
            $account8 = Account::getById($superAccountId8);
            $account9 = Account::getById($superAccountId9);
            $account10 = Account::getById($superAccountId10);
            $account11 = Account::getById($superAccountId11);
            $account12 = Account::getById($superAccountId12);
            $this->assertEquals('4455', $account1->officePhone);
            $this->assertEquals('4455', $account2->officePhone);
            $this->assertEquals('4455', $account3->officePhone);
            $this->assertEquals('4455', $account4->officePhone);
            $this->assertEquals('4455', $account5->officePhone);
            $this->assertEquals('4455', $account6->officePhone);
            $this->assertEquals('4455', $account7->officePhone);
            $this->assertEquals('4455', $account8->officePhone);
            $this->assertEquals('4455', $account9->officePhone);
            $this->assertEquals('4455', $account10->officePhone);
            $this->assertEquals('4455', $account11->officePhone);
            $this->assertEquals('4455', $account12->officePhone);

            //Run Mass Update using progress save.
            $pageSize = Yii::app()->pagination->getForCurrentUserByType('massEditProgressPageSize');
            $this->assertEquals(5, $pageSize);
            Yii::app()->pagination->setForCurrentUserByType('massEditProgressPageSize', 1);
            //The page size is smaller than the result set, so it should exit.
            $this->runControllerWithExitExceptionAndGetContent('accounts/default/massEdit');
            //save Modal MassEdit using progress load for page 2, 3 and 4.
            $this->setGetArray(array('selectAll' => '1', 'Account_page' => 2));
            $content = $this->runControllerWithNoExceptionsAndGetContent('accounts/default/massEditProgressSave');
            $this->assertFalse(strpos($content, '"value":16') === false);
            $this->setGetArray(array('selectAll' => '1', 'Account_page' => 3));
            $content = $this->runControllerWithNoExceptionsAndGetContent('accounts/default/massEditProgressSave');
            $this->assertFalse(strpos($content, '"value":25') === false);
            $this->setGetArray(array('selectAll' => '1', 'Account_page' => 4));
            $content = $this->runControllerWithNoExceptionsAndGetContent('accounts/default/massEditProgressSave');
            $this->assertFalse(strpos($content, '"value":33') === false);
            //Set page size back to old value.
            Yii::app()->pagination->setForCurrentUserByType('massEditProgressPageSize', $pageSize);

            //Autocomplete for Account
            $this->setGetArray(array('term' => 'super'));
            $this->runControllerWithNoExceptionsAndGetContent('accounts/default/autoComplete');

            //actionModalList
            $this->setGetArray(array(
                'modalTransferInformation' => array('sourceIdFieldId' => 'x', 'sourceNameFieldId' => 'y')
            ));
            $this->runControllerWithNoExceptionsAndGetContent('accounts/default/modalList');

            //actionAuditEventsModalList
            $this->setGetArray(array('id' => $superAccountId));
            $this->resetPostArray();
            $this->runControllerWithNoExceptionsAndGetContent('accounts/default/auditEventsModalList');
        }

        
    }
?>
