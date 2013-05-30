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

    class MarketingListPublicControllerWalkthroughTest extends ZurmoWalkthroughBaseTest
    {
        protected $subscribeUrl             = '/marketingLists/public/subscribe';

        protected $unsubscribeUrl           = '/marketingLists/public/unsubscribe';

        protected $optOutUrl                = '/marketingLists/public/optOut';

        protected $manageSubscriptionsUrl   = '/marketingLists/public/manageSubscriptions';

        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
            ReadPermissionsOptimizationUtil::rebuild();
        }

        /**
         * @expectedException CHttpException
         * @expectedMessage Your request is invalid.
         */
        public function testSubscribeActionThrowCHttpExceptionWithoutHash()
        {
            $this->runControllerWithNoExceptionsAndGetContent($this->subscribeUrl);
        }

        /**
         * @depends testSubscribeActionThrowCHttpExceptionWithoutHash
         * @expectedException CHttpException
         * @expectedMessage Your request is invalid.
         */
        public function testUnsubscribeActionThrowCHttpExceptionWithoutHash()
        {
            $this->runControllerWithNoExceptionsAndGetContent($this->unsubscribeUrl);
        }

        /**
         * @depends testUnsubscribeActionThrowCHttpExceptionWithoutHash
         * @expectedException CHttpException
         * @expectedMessage Your request is invalid.
         */
        public function testOptOutActionThrowCHttpExceptionWithoutHash()
        {
            $this->runControllerWithNoExceptionsAndGetContent($this->optOutUrl);
        }

        /**
         * @depends testOptOutActionThrowCHttpExceptionWithoutHash
         * @expectedException CHttpException
         * @expectedMessage Your request is invalid.
         */
        public function testManageSubscriptionsActionThrowCHttpExceptionWithoutHash()
        {
            $this->runControllerWithNoExceptionsAndGetContent($this->manageSubscriptionsUrl);
        }

        /**
         * @depends testManageSubscriptionsActionThrowCHttpExceptionWithoutHash
         * @expectedException NotSupportedException
         */
        public function testSubscribeActionThrowNotSupportedExceptionWithNonHexadecimalHash()
        {
            $hash       = 'Bo9iemeigh6muath8chu2leThohn8Abimoh5rebaihei4aiM1uFoThaith9eng1sei8aisuHu1ugoophiewoe1ieloo';
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $this->runControllerWithNoExceptionsAndGetContent($this->subscribeUrl);
        }

        /**
         * @depends testSubscribeActionThrowNotSupportedExceptionWithNonHexadecimalHash
         * @expectedException NotSupportedException
         */
        public function testUnsubscribeActionThrowNotSupportedExceptionWithNonHexadecimalHash()
        {
            $hash       = 'Bo9iemeigh6muath8chu2leThohn8Abimoh5rebaihei4aiM1uFoThaith9eng1sei8aisuHu1ugoophiewoe1ieloo';
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $this->runControllerWithNoExceptionsAndGetContent($this->unsubscribeUrl);
        }

        /**
         * @depends testUnsubscribeActionThrowNotSupportedExceptionWithNonHexadecimalHash
         * @expectedException NotSupportedException
         */
        public function testOptOutActionThrowNotSupportedExceptionWithNonHexadecimalHash()
        {
            $hash       = 'Bo9iemeigh6muath8chu2leThohn8Abimoh5rebaihei4aiM1uFoThaith9eng1sei8aisuHu1ugoophiewoe1ieloo';
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $this->runControllerWithNoExceptionsAndGetContent($this->optOutUrl);
        }

        /**
         * @depends testOptOutActionThrowNotSupportedExceptionWithNonHexadecimalHash
         * @expectedException NotSupportedException
         */
        public function testManageSubscriptionsActionThrowNotSupportedExceptionWithNonHexadecimalHash()
        {
            $hash       = 'Bo9iemeigh6muath8chu2leThohn8Abimoh5rebaihei4aiM1uFoThaith9eng1sei8aisuHu1ugoophiewoe1ieloo';
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $this->runControllerWithNoExceptionsAndGetContent($this->manageSubscriptionsUrl);
        }

        /**
         * @depends testManageSubscriptionsActionThrowNotSupportedExceptionWithNonHexadecimalHash
         * @expectedException NotSupportedException
         */
        public function testSubscribeActionThrowNotSupportedExceptionForIndecipherableHexadecimalHash()
        {
            $hash       = 'DEDF8F6C80D20528130EBBFBD293E49C9E2F0CBFDE8995FFE4EEAD8EC8F00B70';
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $this->runControllerWithNoExceptionsAndGetContent($this->subscribeUrl);
        }

        /**
         * @depends testSubscribeActionThrowNotSupportedExceptionForIndecipherableHexadecimalHash
         * @expectedException NotSupportedException
         */
        public function testUnsubscribeActionThrowNotSupportedExceptionForIndecipherableHexadecimalHash()
        {
            $hash       = 'DEDF8F6C80D20528130EBBFBD293E49C9E2F0CBFDE8995FFE4EEAD8EC8F00B70';
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $this->runControllerWithNoExceptionsAndGetContent($this->unsubscribeUrl);
        }

        /**
         * @depends testUnsubscribeActionThrowNotSupportedExceptionForIndecipherableHexadecimalHash
         * @expectedException NotSupportedException
         */
        public function testOptOutActionThrowNotSupportedExceptionForIndecipherableHexadecimalHash()
        {
            $hash       = 'DEDF8F6C80D20528130EBBFBD293E49C9E2F0CBFDE8995FFE4EEAD8EC8F00B70';
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $this->runControllerWithNoExceptionsAndGetContent($this->optOutUrl);
        }

        /**
         * @depends testOptOutActionThrowNotSupportedExceptionForIndecipherableHexadecimalHash
         * @expectedException NotSupportedException
         */
        public function testManageSubscriptionsActionThrowNotSupportedExceptionForIndecipherableHexadecimalHash()
        {
            $hash       = 'DEDF8F6C80D20528130EBBFBD293E49C9E2F0CBFDE8995FFE4EEAD8EC8F00B70';
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $this->runControllerWithNoExceptionsAndGetContent($this->manageSubscriptionsUrl);
        }

        /**
         * @depends testManageSubscriptionsActionThrowNotSupportedExceptionForIndecipherableHexadecimalHash
         * @expectedException NotSupportedException
         */
        public function testSubscribeActionThrowNotSupportedExceptionForDecipherableHexadecimalHashWithMissingParameters()
        {
            $queryStringArray = array(
                'keyOne'    => 'valueOne',
                'keyTwo'    => 'valueTwo',
                'keyThree'  => 'ValueThree',
            );
            $resolveHashForQueryStringArrayFunction = static::getProtectedMethod('EmailMessageActivityUtil',
                                                                                    'resolveHashForQueryStringArray');
            $hash       = $resolveHashForQueryStringArrayFunction->invokeArgs(null, array($queryStringArray));
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $this->runControllerWithNoExceptionsAndGetContent($this->subscribeUrl);
        }

        /**
         * @depends testSubscribeActionThrowNotSupportedExceptionForDecipherableHexadecimalHashWithMissingParameters
         * @expectedException NotSupportedException
         */
        public function testUnsubscribeActionThrowNotSupportedExceptionForDecipherableHexadecimalHashWithMissingParameters()
        {
            $queryStringArray = array(
                'keyOne'    => 'valueOne',
                'keyTwo'    => 'valueTwo',
                'keyThree'  => 'ValueThree',
            );
            $resolveHashForQueryStringArrayFunction = static::getProtectedMethod('EmailMessageActivityUtil',
                                                                                    'resolveHashForQueryStringArray');
            $hash       = $resolveHashForQueryStringArrayFunction->invokeArgs(null, array($queryStringArray));
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $this->runControllerWithNoExceptionsAndGetContent($this->unsubscribeUrl);
        }

        /**
         * @depends testUnsubscribeActionThrowNotSupportedExceptionForDecipherableHexadecimalHashWithMissingParameters
         * @expectedException NotSupportedException
         */
        public function testOptOutActionThrowNotSupportedExceptionForDecipherableHexadecimalHashWithMissingParameters()
        {
            $queryStringArray = array(
                'keyOne'    => 'valueOne',
                'keyTwo'    => 'valueTwo',
                'keyThree'  => 'ValueThree',
            );
            $resolveHashForQueryStringArrayFunction = static::getProtectedMethod('EmailMessageActivityUtil',
                                                                                    'resolveHashForQueryStringArray');
            $hash       = $resolveHashForQueryStringArrayFunction->invokeArgs(null, array($queryStringArray));
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $this->runControllerWithNoExceptionsAndGetContent($this->optOutUrl);
        }

        /**
         * @depends testOptOutActionThrowNotSupportedExceptionForDecipherableHexadecimalHashWithMissingParameters
         * @expectedException NotSupportedException
         */
        public function testManageSubscriptionsActionThrowNotSupportedExceptionForDecipherableHexadecimalHashWithMissingParameters()
        {
            $queryStringArray = array(
                'keyOne'    => 'valueOne',
                'keyTwo'    => 'valueTwo',
                'keyThree'  => 'ValueThree',
            );
            $resolveHashForQueryStringArrayFunction = static::getProtectedMethod('EmailMessageActivityUtil',
                                                                                    'resolveHashForQueryStringArray');
            $hash       = $resolveHashForQueryStringArrayFunction->invokeArgs(null, array($queryStringArray));
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $this->runControllerWithNoExceptionsAndGetContent($this->manageSubscriptionsUrl);
        }

        /**
         * @depends testManageSubscriptionsActionThrowNotSupportedExceptionForDecipherableHexadecimalHashWithMissingParameters
         * @expectedException NotFoundException
         */
        public function testSubscribeActionThrowNotFoundExceptionForInvalidMarketingListId()
        {
            $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');
            $contact    = ContactTestHelper::createContactByNameForOwner('contact 01', Yii::app()->user->userModel);
            Yii::app()->user->userModel = null;
            $personId   = $contact->getClassId('Person');
            $hash       = EmailMessageActivityUtil::resolveHashForFooter($personId, 100);
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $this->runControllerWithNoExceptionsAndGetContent($this->subscribeUrl);
        }

        /**
         * @depends testSubscribeActionThrowNotFoundExceptionForInvalidMarketingListId
         * @expectedException NotFoundException
         */
        public function testUnsubscribeActionThrowNotFoundExceptionForInvalidMarketingListId()
        {
            $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');
            $contact    = ContactTestHelper::createContactByNameForOwner('contact 02', Yii::app()->user->userModel);
            Yii::app()->user->userModel = null;
            $personId   = $contact->getClassId('Person');
            $hash       = EmailMessageActivityUtil::resolveHashForFooter($personId, 100);
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $this->runControllerWithNoExceptionsAndGetContent($this->unsubscribeUrl);
        }

        /**
         * @depends testUnsubscribeActionThrowNotFoundExceptionForInvalidMarketingListId
         * @expectedException NotFoundException
         */
        public function testOptoutActionThrowNotFoundExceptionForInvalidMarketingListId()
        {
            $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');
            $contact    = ContactTestHelper::createContactByNameForOwner('contact 03', Yii::app()->user->userModel);
            Yii::app()->user->userModel = null;
            $personId   = $contact->getClassId('Person');
            $hash       = EmailMessageActivityUtil::resolveHashForFooter($personId, 100);
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $this->runControllerWithNoExceptionsAndGetContent($this->optOutUrl);
        }

        /**
         * @depends testOptoutActionThrowNotFoundExceptionForInvalidMarketingListId
         */
        public function testManageSubscriptionsActionDoesNotThrowNotFoundExceptionForInvalidMarketingListIdWithNoMarketingLists()
        {
            $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');
            $contact    = ContactTestHelper::createContactByNameForOwner('contact 04', Yii::app()->user->userModel);
            Yii::app()->user->userModel = null;
            $personId   = $contact->getClassId('Person');
            $hash       = EmailMessageActivityUtil::resolveHashForFooter($personId, 100);
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $content = $this->runControllerWithNoExceptionsAndGetContent($this->manageSubscriptionsUrl);
            $this->assertTrue(strpos($content, '<title>ZurmoCRM - Manage Subscriptions</title>') !== false);
            $this->assertTrue(strpos($content, '/views/MarketingListsManageSubscriptionsPageView.php') !== false);
            $this->assertTrue(strpos($content, '<div id="MarketingListsManageSubscriptionsPageView" ' .
                                                    'class="ZurmoPageView PageView">') !== false);
            $this->assertTrue(strpos($content, '<div class="GridView">') !== false);
            $this->assertTrue(strpos($content, 'modules/zurmo/views/HeaderLinksView.php') !== false);
            $this->assertTrue(strpos($content, '<div id="HeaderLinksView">') !== false);
            $this->assertTrue(strpos($content, '<div id="corp-logo">') !== false);
            $this->assertTrue(strpos($content, '/home/default"><img src="') !== false);
            $this->assertTrue(strpos($content, '/themes/default/images/Zurmo_logo.png" alt="Zurmo Logo"  ' .
                                                    'height="32" width="107" /></a>') !== false);
            $this->assertTrue(strpos($content, 'core/views/FlashMessageView.php') !== false);
            $this->assertTrue(strpos($content, '<div id="FlashMessageView">') !== false);
            $this->assertTrue(strpos($content, '<div id = "FlashMessageBar">') !== false);
            $this->assertTrue(strpos($content, '/views/MarketingListsManageSubscriptionsListView.php') !== false);
            $this->assertTrue(strpos($content, '<div id="MarketingListsManageSubscriptionsListView" ' .
                                                    'class="MetadataView">') !== false);
            $this->assertTrue(strpos($content, '<div class="wrapper">') !== false);
            $this->assertTrue(strpos($content, '<h1><span class="truncated-title"><span class="ellipsis-content">' .
                                                    'My Subscriptions</span></span></h1>') !== false);
            $this->assertTrue(strpos($content, '<div class="wide" ' .
                                                    'id="marketingLists-manageSubscriptionsList">') !== false);
            $this->assertTrue(strpos($content, '<colgroup><col style="width:80%" />' .
                                                    '<col style="width:20%" /></colgroup>') !== false);
            $this->assertTrue(strpos($content, '<tr><th>Name</th><th>Toggle Subscription</th></tr>') !== false);
            $this->assertTrue(strpos($content, '<td><a class="simple-link marketingListsManage' .
                                                    'SubscriptionListView-toggleUnsubscribed"') !== false);
            $this->assertTrue(strpos($content, '/marketingLists/public/optOut?hash=') !== false);
            $this->assertTrue(strpos($content, '3D%253D">Unsubscribe All/OptOut</a></td></tr></table>') !== false);
            $this->assertTrue(strpos($content, 'modules/zurmo/views/FooterView.php') !== false);
            $this->assertTrue(strpos($content, '<div id="FooterView">') !== false);
            $this->assertTrue(strpos($content, '<a href="http://www.zurmo.com" id="credit-link" ' .
                                                    'class="clearfix">') !== false);
            $this->assertTrue(strpos($content, '<span>Copyright &#169; Zurmo Inc., 2013. ' .
                                                    'All rights reserved.</span></a>') !== false);
        }

        /**
         * @depends testManageSubscriptionsActionDoesNotThrowNotFoundExceptionForInvalidMarketingListIdWithNoMarketingLists
         * @expectedException NotFoundException
         */
        public function testSubscribeActionThrowsNotFoundExceptionForInvalidPersonlId()
        {
            $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');
            $marketingList  = MarketingListTestHelper::createMarketingListByName('marketingList 01',
                                                                                    'description',
                                                                                    'fromName',
                                                                                    'from@domain.com',
                                                                                    true);
            Yii::app()->user->userModel = null;
            $hash       = EmailMessageActivityUtil::resolveHashForFooter(100, $marketingList->id);
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $this->runControllerWithNoExceptionsAndGetContent($this->subscribeUrl);
        }

        /**
         * @depends testSubscribeActionThrowsNotFoundExceptionForInvalidPersonlId
         * @expectedException NotFoundException
         */
        public function testUnsubscribeActionThrowsNotFoundExceptionForInvalidPersonlId()
        {
            $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');
            $marketingList  = MarketingListTestHelper::createMarketingListByName('marketingList 02',
                                                                                    'description',
                                                                                    'fromName',
                                                                                    'from@domain.com',
                                                                                    false);
            Yii::app()->user->userModel = null;
            $hash       = EmailMessageActivityUtil::resolveHashForFooter(100, $marketingList->id);
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $this->runControllerWithNoExceptionsAndGetContent($this->unsubscribeUrl);
        }

        /**
         * @depends testUnsubscribeActionThrowsNotFoundExceptionForInvalidPersonlId
         * @expectedException NotFoundException
         */
        public function testOptOutActionThrowsNotFoundExceptionForInvalidPersonlId()
        {
            $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');
            $marketingList  = MarketingListTestHelper::createMarketingListByName('marketingList 03',
                                                                                    'description',
                                                                                    'fromName',
                                                                                    'from@domain.com',
                                                                                    true);
            Yii::app()->user->userModel = null;
            $hash       = EmailMessageActivityUtil::resolveHashForFooter(100, $marketingList->id);
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $this->runControllerWithNoExceptionsAndGetContent($this->optOutUrl);
        }

        /**
         * @depends testOptOutActionThrowsNotFoundExceptionForInvalidPersonlId
         * @expectedException NotFoundException
         */
        public function testManageSubscriptionsActionThrowsNotFoundExceptionForInvalidPersonlId()
        {
            $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');
            $marketingList  = MarketingListTestHelper::createMarketingListByName('marketingList 04',
                                                                                    'description',
                                                                                    'fromName',
                                                                                    'from@domain.com',
                                                                                    false);
            Yii::app()->user->userModel = null;
            $hash       = EmailMessageActivityUtil::resolveHashForFooter(100, $marketingList->id);
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $this->runControllerWithNoExceptionsAndGetContent($this->manageSubscriptionsUrl);
        }

        /**
         * @depends testManageSubscriptionsActionThrowsNotFoundExceptionForInvalidPersonlId
         */
        public function testManageSubscriptionsAction()
        {
            $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');
            $contact        = ContactTestHelper::createContactByNameForOwner('contact 05', Yii::app()->user->userModel);
            $personId       = $contact->getClassId('Person');
            $subscribedIds  = array();
            for ($index = 1; $index < 5; $index++)
            {
                $marketingListName      = 'marketingList 0' . $index;
                $marketingList          = MarketingList::getByName($marketingListName);
                $marketingList          = $marketingList[0];
                $unsubscribed           = ($index % 2);
                if (!$unsubscribed)
                {
                    $subscribedIds[]    = $marketingList->id;
                }
                MarketingListMemberTestHelper::createMarketingListMember($unsubscribed, $marketingList, $contact);
            }
            Yii::app()->user->userModel = null;
            $hash       = EmailMessageActivityUtil::resolveHashForFooter($personId, $marketingList->id);
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $content    = $this->runControllerWithNoExceptionsAndGetContent($this->manageSubscriptionsUrl);
            $this->assertTrue(strpos($content, '<title>ZurmoCRM - Manage Subscriptions</title>') !== false);
            $this->assertTrue(strpos($content, '/views/MarketingListsManageSubscriptionsPageView.php') !== false);
            $this->assertTrue(strpos($content, '<div id="MarketingListsManageSubscriptionsPageView" ' .
                                                    'class="ZurmoPageView PageView">') !== false);
            $this->assertTrue(strpos($content, '<div class="GridView">') !== false);
            $this->assertTrue(strpos($content, 'modules/zurmo/views/HeaderLinksView.php') !== false);
            $this->assertTrue(strpos($content, '<div id="HeaderLinksView">') !== false);
            $this->assertTrue(strpos($content, '<div id="corp-logo">') !== false);
            $this->assertTrue(strpos($content, '/home/default"><img src="') !== false);
            $this->assertTrue(strpos($content, '/themes/default/images/Zurmo_logo.png" alt="Zurmo Logo"  ' .
                                                    'height="32" width="107" /></a>') !== false);
            $this->assertTrue(strpos($content, 'core/views/FlashMessageView.php') !== false);
            $this->assertTrue(strpos($content, '<div id="FlashMessageView">') !== false);
            $this->assertTrue(strpos($content, '<div id = "FlashMessageBar">') !== false);
            $this->assertTrue(strpos($content, '/views/MarketingListsManageSubscriptionsListView.php') !== false);
            $this->assertTrue(strpos($content, '<div id="MarketingListsManageSubscriptionsListView" ' .
                                                    'class="MetadataView">') !== false);
            $this->assertTrue(strpos($content, '<div class="wrapper">') !== false);
            $this->assertTrue(strpos($content, '<h1><span class="truncated-title"><span class="ellipsis-content">' .
                                                    'My Subscriptions</span></span></h1>') !== false);
            $this->assertTrue(strpos($content, '<div class="wide" ' .
                                                    'id="marketingLists-manageSubscriptionsList">') !== false);
            $this->assertTrue(strpos($content, '<colgroup><col style="width:80%" />' .
                                                    '<col style="width:20%" /></colgroup>') !== false);
            $this->assertTrue(strpos($content, '<tr><th>Name</th><th>Toggle Subscription</th></tr>') !== false);
            $this->assertTrue(strpos($content, '<td><a class="simple-link marketingListsManage' .
                                                    'SubscriptionListView-toggleUnsubscribed"') !== false);
            $this->assertTrue(strpos($content, '/marketingLists/public/optOut?hash=') !== false);
            $this->assertTrue(strpos($content, '3D%253D">Unsubscribe All/OptOut</a></td></tr></table>') !== false);
            $this->assertTrue(strpos($content, 'modules/zurmo/views/FooterView.php') !== false);
            $this->assertTrue(strpos($content, '<div id="FooterView">') !== false);
            $this->assertTrue(strpos($content, '<a href="http://www.zurmo.com" id="credit-link" ' .
                                                    'class="clearfix">') !== false);
            $this->assertTrue(strpos($content, '<span>Copyright &#169; Zurmo Inc., 2013. ' .
                                                    'All rights reserved.</span></a>') !== false);
            $this->assertTrue(strpos($content, '<tr><td>marketingList 02</td>') !== false);
            $this->assertTrue(strpos($content, '<td><div class="switch">') !== false);
            $this->assertTrue(strpos($content, '<div class="switch-state clearfix">') !== false);
            $this->assertTrue(strpos($content, '/marketingLists/public/subscribe?hash=') !== false);
            $this->assertTrue(strpos($content, '%253D%253D" id="marketingListsManage' .
                                                    'SubscriptionListView-toggleUnsubscribed_') !== false);
            $this->assertTrue(strpos($content, 'checked="checked" type="radio" name="marketingListsManage' .
                                                    'SubscriptionListView-toggleUnsubscribed_') !== false);
            $this->assertTrue(strpos($content, '<label for="marketingListsManage' .
                                                    'SubscriptionListView-toggleUnsubscribed_') !== false);
            $this->assertTrue(strpos($content, '_0">Subscribe</label></div>') !== false);
            $this->assertTrue(strpos($content, '/marketingLists/public/unsubscribe?hash=') !== false);
            $this->assertTrue(strpos($content, '_1">Unsubcribe</label></div></div></td></tr>') !== false);
            $this->assertTrue(strpos($content, '<tr><td>marketingList 01</td>') !== false);
            $this->assertTrue(strpos($content, '<tr><td>marketingList 03</td>') !== false);
            $this->assertTrue(strpos($content, '<tr><td>marketingList 04</td>') !== false);
            $this->assertEquals(4, substr_count($content, '_0">Subscribe</label></div>'));
            $this->assertEquals(4, substr_count($content, '_1">Unsubcribe</label></div></div></td></tr>'));
            foreach ($subscribedIds as $subscribedId)
            {
                $this->assertTrue(strpos($content, 'checked="checked" type="radio" name="marketingListsManage' .
                                                'SubscriptionListView-toggleUnsubscribed_' . $subscribedId) !== false);
            }
        }

        /**
         * @depends testManageSubscriptionsAction
         */
        public function testSubscribeActionToPrivateMarketingList()
        {
            $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');
            $marketingList  = MarketingList::getByName('marketingList 04');
            $this->assertNotEmpty($marketingList);
            $marketingList  = $marketingList[0];
            $contact        = Contact::getByName('contact 05 contact 05son');
            $this->assertNotEmpty($contact);
            $contact        = $contact[0];
            $member         = MarketingListMember::getByMarketingListIdContactIdAndSubscribed($marketingList->id,
                                                                                                        $contact->id,
                                                                                                        0);
            $this->assertNotEmpty($member);
            $member[0]->delete();
            $personId       = $contact->getClassId('Person');
            Yii::app()->user->userModel = null;
            $hash           = EmailMessageActivityUtil::resolveHashForFooter($personId, $marketingList->id);
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $this->runControllerWithRedirectExceptionAndGetUrl($this->subscribeUrl);
            $content = $this->runControllerWithNoExceptionsAndGetContent($this->manageSubscriptionsUrl);
            $this->assertNotEmpty($content);
            $this->assertTrue(strpos($content, '<tr><td>marketingList 04</td>') === false);
        }

        /**
         * @depends testSubscribeActionToPrivateMarketingList
         */
        public function testSubscribeActionToPublicMarketingList()
        {
            $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');
            $marketingList      = MarketingList::getByName('marketingList 03');
            $this->assertNotEmpty($marketingList);
            $marketingList      = $marketingList[0];
            $marketingListId    = $marketingList->id;
            $contact            = Contact::getByName('contact 05 contact 05son');
            $this->assertNotEmpty($contact);
            $contact            = $contact[0];
            $member             = MarketingListMember::getByMarketingListIdContactIdAndSubscribed($marketingList->id,
                                                                                                    $contact->id,
                                                                                                    1);
            $this->assertNotEmpty($member);
            $member[0]->delete();
            $personId       = $contact->getClassId('Person');
            Yii::app()->user->userModel = null;
            $hash           = EmailMessageActivityUtil::resolveHashForFooter($personId, $marketingListId);
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $content    = $this->runControllerWithNoExceptionsAndGetContent($this->manageSubscriptionsUrl);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                                $marketingListId . '_0" type="radio" name="marketingListsManage' .
                                'SubscriptionListView-toggleUnsubscribed_' . $marketingListId) !== false);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                                $marketingListId . '_1" checked="checked" type="radio" name="marketingListsManage' .
                                'SubscriptionListView-toggleUnsubscribed_' . $marketingListId) !== false);
            @$this->runControllerWithRedirectExceptionAndGetUrl($this->subscribeUrl);
            $content = $this->runControllerWithNoExceptionsAndGetContent($this->manageSubscriptionsUrl);
            $this->assertNotEmpty($content);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                                $marketingListId . '_0" checked="checked" type="radio" name="marketingListsManage' .
                                'SubscriptionListView-toggleUnsubscribed_' . $marketingListId) !== false);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                                $marketingListId . '_1" type="radio" name="marketingListsManage' .
                                'SubscriptionListView-toggleUnsubscribed_' . $marketingListId) !== false);
        }

        /**
         * @depends testSubscribeActionToPublicMarketingList
         */
        public function testSubscribeActionToPublicMarketingListAlreadyASubscriberOf()
        {
            $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');
            $marketingList      = MarketingList::getByName('marketingList 03');
            $this->assertNotEmpty($marketingList);
            $marketingList      = $marketingList[0];
            $marketingListId    = $marketingList->id;
            $contact            = Contact::getByName('contact 05 contact 05son');
            $this->assertNotEmpty($contact);
            $contact            = $contact[0];
            $member             = MarketingListMember::getByMarketingListIdContactIdAndSubscribed($marketingList->id,
                                                                                                    $contact->id,
                                                                                                    0);
            $this->assertNotEmpty($member);
            $this->assertEquals(0, $member[0]->unsubscribed);
            $personId       = $contact->getClassId('Person');
            Yii::app()->user->userModel = null;
            $hash           = EmailMessageActivityUtil::resolveHashForFooter($personId, $marketingListId);
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $content    = $this->runControllerWithNoExceptionsAndGetContent($this->manageSubscriptionsUrl);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                                $marketingListId . '_0" checked="checked" type="radio" name="marketingListsManage' .
                                'SubscriptionListView-toggleUnsubscribed_' . $marketingListId) !== false);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                                $marketingListId . '_1" type="radio" name="marketingListsManage' .
                                'SubscriptionListView-toggleUnsubscribed_' . $marketingListId) !== false);
            $this->runControllerWithRedirectExceptionAndGetUrl($this->subscribeUrl);
            $content = $this->runControllerWithNoExceptionsAndGetContent($this->manageSubscriptionsUrl);
            $this->assertNotEmpty($content);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                $marketingListId . '_0" checked="checked" type="radio" name="marketingListsManage' .
                'SubscriptionListView-toggleUnsubscribed_' . $marketingListId) !== false);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                $marketingListId . '_1" type="radio" name="marketingListsManage' .
                'SubscriptionListView-toggleUnsubscribed_' . $marketingListId) !== false);
        }

        /**
         * @depends testSubscribeActionToPublicMarketingListAlreadyASubscriberOf
         */
        public function testUnsubscribeActionToPublicMarketingList()
        {
            $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');
            $marketingList      = MarketingList::getByName('marketingList 03');
            $this->assertNotEmpty($marketingList);
            $marketingList      = $marketingList[0];
            $marketingListId    = $marketingList->id;
            $contact            = Contact::getByName('contact 05 contact 05son');
            $this->assertNotEmpty($contact);
            $contact            = $contact[0];
            $member             = MarketingListMember::getByMarketingListIdContactIdAndSubscribed($marketingList->id,
                                                                                                    $contact->id,
                                                                                                    0);
            $this->assertNotEmpty($member);
            $this->assertEquals(0, $member[0]->unsubscribed);
            $personId       = $contact->getClassId('Person');
            Yii::app()->user->userModel = null;
            $hash           = EmailMessageActivityUtil::resolveHashForFooter($personId, $marketingListId);
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $content    = $this->runControllerWithNoExceptionsAndGetContent($this->manageSubscriptionsUrl);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                                $marketingListId . '_0" checked="checked" type="radio" name="marketingListsManage' .
                                'SubscriptionListView-toggleUnsubscribed_' . $marketingListId) !== false);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                                $marketingListId . '_1" type="radio" name="marketingListsManage' .
                                'SubscriptionListView-toggleUnsubscribed_' . $marketingListId) !== false);
            @$this->runControllerWithRedirectExceptionAndGetUrl($this->unsubscribeUrl);
            $content = $this->runControllerWithNoExceptionsAndGetContent($this->manageSubscriptionsUrl);
            $this->assertNotEmpty($content);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                                $marketingListId . '_0" type="radio" name="marketingListsManage' .
                                'SubscriptionListView-toggleUnsubscribed_' . $marketingListId) !== false);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                                $marketingListId . '_1" checked="checked" type="radio" name="marketingListsManage' .
                                'SubscriptionListView-toggleUnsubscribed_' . $marketingListId) !== false);
        }

        /**
         * @depends testUnsubscribeActionToPublicMarketingList
         */
        public function testUnsubscribeActionToPrivateMarketingList()
        {
            $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');
            $marketingList      = MarketingList::getByName('marketingList 04');
            $this->assertNotEmpty($marketingList);
            $marketingList      = $marketingList[0];
            $marketingListId    = $marketingList->id;
            $contact            = Contact::getByName('contact 05 contact 05son');
            $this->assertNotEmpty($contact);
            $contact            = $contact[0];
            $marketingList->addNewMember($contact->id, false, $contact);
            $member             = MarketingListMember::getByMarketingListIdContactIdAndSubscribed($marketingList->id,
                                                                                                        $contact->id,
                                                                                                        0);
            $this->assertNotEmpty($member);
            $this->assertEquals(0, $member[0]->unsubscribed);
            $personId       = $contact->getClassId('Person');
            Yii::app()->user->userModel = null;
            $hash           = EmailMessageActivityUtil::resolveHashForFooter($personId, $marketingListId);
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $content    = $this->runControllerWithNoExceptionsAndGetContent($this->manageSubscriptionsUrl);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                                $marketingListId . '_0" checked="checked" type="radio" name="marketingListsManage' .
                                'SubscriptionListView-toggleUnsubscribed_' . $marketingListId) !== false);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                                $marketingListId . '_1" type="radio" name="marketingListsManage' .
                                'SubscriptionListView-toggleUnsubscribed_' . $marketingListId) !== false);
            @$this->runControllerWithRedirectExceptionAndGetUrl($this->unsubscribeUrl);
            $content = $this->runControllerWithNoExceptionsAndGetContent($this->manageSubscriptionsUrl);
            $this->assertNotEmpty($content);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                                $marketingListId . '_0" type="radio" name="marketingListsManage' .
                                'SubscriptionListView-toggleUnsubscribed_' . $marketingListId) === false);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                                $marketingListId . '_1" checked="checked" type="radio" name="marketingListsManage' .
                                'SubscriptionListView-toggleUnsubscribed_' . $marketingListId) === false);
            $this->assertTrue(strpos($content, '<tr><td>marketingList 04</td>') === false);
        }

        /**
         * @depends testUnsubscribeActionToPrivateMarketingList
         */
        public function testUnsubscribeActionToPublicMarketingListAlreadyUnsubcribedOf()
        {
            $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');
            $marketingList      = MarketingList::getByName('marketingList 03');
            $this->assertNotEmpty($marketingList);
            $marketingList      = $marketingList[0];
            $marketingListId    = $marketingList->id;
            $contact            = Contact::getByName('contact 05 contact 05son');
            $this->assertNotEmpty($contact);
            $contact            = $contact[0];
            $member             = MarketingListMember::getByMarketingListIdContactIdAndSubscribed($marketingList->id,
                                                                                                    $contact->id,
                                                                                                    1);
            $this->assertNotEmpty($member);
            $this->assertEquals(1, $member[0]->unsubscribed);
            $personId       = $contact->getClassId('Person');
            Yii::app()->user->userModel = null;
            $hash           = EmailMessageActivityUtil::resolveHashForFooter($personId, $marketingListId);
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $content    = $this->runControllerWithNoExceptionsAndGetContent($this->manageSubscriptionsUrl);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                                        $marketingListId . '_0" type="radio" name="marketingListsManage' .
                                        'SubscriptionListView-toggleUnsubscribed_' . $marketingListId) !== false);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                                        $marketingListId . '_1" checked="checked" type="radio" name="marketingListsManage' .
                                        'SubscriptionListView-toggleUnsubscribed_' . $marketingListId) !== false);
            $this->runControllerWithRedirectExceptionAndGetUrl($this->unsubscribeUrl);
            $content = $this->runControllerWithNoExceptionsAndGetContent($this->manageSubscriptionsUrl);
            $this->assertNotEmpty($content);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                                    $marketingListId . '_0" type="radio" name="marketingListsManage' .
                                    'SubscriptionListView-toggleUnsubscribed_' . $marketingListId) !== false);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                                    $marketingListId . '_1" checked="checked" type="radio" name="marketingListsManage' .
                                    'SubscriptionListView-toggleUnsubscribed_' . $marketingListId) !== false);
        }

        /**
         * @depends testUnsubscribeActionToPublicMarketingListAlreadyUnsubcribedOf
         */
        public function testUnsubscribeActionToPrivateMarketingListAlreadyUnscribedOf()
        {
            $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');
            $marketingList      = MarketingList::getByName('marketingList 04');
            $this->assertNotEmpty($marketingList);
            $marketingList      = $marketingList[0];
            $marketingListId    = $marketingList->id;
            $contact            = Contact::getByName('contact 05 contact 05son');
            $this->assertNotEmpty($contact);
            $contact            = $contact[0];
            $marketingList->addNewMember($contact->id, true, $contact);
            $member             = MarketingListMember::getByMarketingListIdContactIdAndSubscribed($marketingList->id,
                                                                                                    $contact->id,
                                                                                                    1);
            $this->assertNotEmpty($member);
            $this->assertEquals(1, $member[0]->unsubscribed);
            $personId       = $contact->getClassId('Person');
            Yii::app()->user->userModel = null;
            $hash           = EmailMessageActivityUtil::resolveHashForFooter($personId, $marketingListId);
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $content    = $this->runControllerWithNoExceptionsAndGetContent($this->manageSubscriptionsUrl);
            $this->assertTrue(strpos($content, '<tr><td>marketingList 04</td>') === false);
            $this->runControllerWithRedirectExceptionAndGetUrl($this->unsubscribeUrl);
            $content = $this->runControllerWithNoExceptionsAndGetContent($this->manageSubscriptionsUrl);
            $this->assertNotEmpty($content);
            $this->assertTrue(strpos($content, '<tr><td>marketingList 04</td>') === false);
        }

        /**
         * @depends testUnsubscribeActionToPrivateMarketingListAlreadyUnscribedOf
         */
        public function testUnsubscribeActionToPublicMarketingListNotEvenAMemberOf()
        {
            $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');
            $marketingList      = MarketingList::getByName('marketingList 03');
            $this->assertNotEmpty($marketingList);
            $marketingList      = $marketingList[0];
            $marketingListId    = $marketingList->id;
            $contact            = Contact::getByName('contact 05 contact 05son');
            $this->assertNotEmpty($contact);
            $contact            = $contact[0];
            $member             = MarketingListMember::getByMarketingListIdContactIdAndSubscribed($marketingList->id,
                                                                                                    $contact->id,
                                                                                                    1);
            $this->assertNotEmpty($member);
            $member[0]->delete();
            $personId       = $contact->getClassId('Person');
            Yii::app()->user->userModel = null;
            $hash           = EmailMessageActivityUtil::resolveHashForFooter($personId, $marketingListId);
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $content    = $this->runControllerWithNoExceptionsAndGetContent($this->manageSubscriptionsUrl);
            $this->assertTrue(strpos($content, '<tr><td>marketingList 03</td>') !== false);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                                $marketingListId . '_0" type="radio" name="marketingListsManage' .
                                'SubscriptionListView-toggleUnsubscribed_' . $marketingListId) !== false);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                                $marketingListId . '_1" checked="checked" type="radio" name="marketingListsManage' .
                                'SubscriptionListView-toggleUnsubscribed_' . $marketingListId) !== false);
            $this->runControllerWithRedirectExceptionAndGetUrl($this->unsubscribeUrl);
            $content = $this->runControllerWithNoExceptionsAndGetContent($this->manageSubscriptionsUrl);
            $this->assertNotEmpty($content);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                                $marketingListId . '_0" type="radio" name="marketingListsManage' .
                                'SubscriptionListView-toggleUnsubscribed_' . $marketingListId) !== false);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                                $marketingListId . '_1" checked="checked" type="radio" name="marketingListsManage' .
                                'SubscriptionListView-toggleUnsubscribed_' . $marketingListId) !== false);
            $this->assertTrue(strpos($content, '<tr><td>marketingList 03</td>') !== false);
        }

        /**
         * @depends testUnsubscribeActionToPublicMarketingListNotEvenAMemberOf
         */
        public function testUnsubscribeActionToPrivateMarketingListNotEvenAMemberOf()
        {
            $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');
            $marketingList      = MarketingList::getByName('marketingList 04');
            $this->assertNotEmpty($marketingList);
            $marketingList      = $marketingList[0];
            $marketingListId    = $marketingList->id;
            $contact            = Contact::getByName('contact 05 contact 05son');
            $this->assertNotEmpty($contact);
            $contact            = $contact[0];
            $member             = MarketingListMember::getByMarketingListIdContactIdAndSubscribed($marketingList->id,
                                                                                                    $contact->id,
                                                                                                    1);
            $this->assertNotEmpty($member);
            $member[0]->delete();
            $personId       = $contact->getClassId('Person');
            Yii::app()->user->userModel = null;
            $hash           = EmailMessageActivityUtil::resolveHashForFooter($personId, $marketingListId);
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $content    = $this->runControllerWithNoExceptionsAndGetContent($this->manageSubscriptionsUrl);
            $this->assertTrue(strpos($content, '<tr><td>marketingList 04</td>') === false);
            $this->runControllerWithRedirectExceptionAndGetUrl($this->unsubscribeUrl);
            $content = $this->runControllerWithNoExceptionsAndGetContent($this->manageSubscriptionsUrl);
            $this->assertNotEmpty($content);
            $this->assertTrue(strpos($content, '<tr><td>marketingList 04</td>') === false);
        }

        /**
         * @depends testUnsubscribeActionToPrivateMarketingListNotEvenAMemberOf
         */
        public function testOptOutAction()
        {
            $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');
            $contact            = Contact::getByName('contact 05 contact 05son');
            $this->assertNotEmpty($contact);
            $contact            = $contact[0];
            $this->assertEquals(0, $contact->primaryEmail->optOut);
            $personId           = $contact->getClassId('Person');
            $marketingListIds   = array();
            for ($index = 1; $index < 5; $index++)
            {
                $marketingListName  = 'marketingList 0' . $index;
                $marketingList      = MarketingList::getByName($marketingListName);
                $this->assertNotEmpty($marketingList);
                $marketingListIds[] = $marketingList[0]->id;
                if ($index === 4)
                {
                    $marketingList[0]->addNewMember($contact->id, false, $contact);
                }
            }
            Yii::app()->user->userModel = null;
            $hash           = EmailMessageActivityUtil::resolveHashForFooter($personId, $marketingListIds[0]);
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            $content    = $this->runControllerWithNoExceptionsAndGetContent($this->manageSubscriptionsUrl);
            $this->assertTrue(strpos($content, '<tr><td>marketingList 01</td>') !== false);
            $this->assertTrue(strpos($content, '<tr><td>marketingList 02</td>') !== false);
            $this->assertTrue(strpos($content, '<tr><td>marketingList 03</td>') !== false);
            $this->assertTrue(strpos($content, '<tr><td>marketingList 04</td>') !== false);
            $this->assertTrue(strpos($content, 'marketingLists/public/subscribe?hash=') !== false);
            $this->assertTrue(strpos($content, 'id="marketingListsManage' .
                                        'SubscriptionListView-toggleUnsubscribed_') !== false);
            $this->assertTrue(strpos($content, '%253D%253D" id="marketingListsManage' .
                                        'SubscriptionListView-toggleUnsubscribed_') !== false);
            $this->assertTrue(strpos($content, 'type="radio" name="marketingListsManage' .
                                        'SubscriptionListView-toggleUnsubscribed_') !== false);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                                $marketingListIds[0] . '_1" checked="checked" type="radio" name="marketingListsManage' .
                                'SubscriptionListView-toggleUnsubscribed_' . $marketingListIds[0]) !== false);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                                $marketingListIds[1] . '_0" checked="checked" type="radio" name="marketingListsManage' .
                                'SubscriptionListView-toggleUnsubscribed_' . $marketingListIds[1]) !== false);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                                $marketingListIds[2] . '_1" checked="checked" type="radio" name="marketingListsManage' .
                                'SubscriptionListView-toggleUnsubscribed_' . $marketingListIds[2]) !== false);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                                $marketingListIds[3] . '_0" checked="checked" type="radio" name="marketingListsManage' .
                                'SubscriptionListView-toggleUnsubscribed_' . $marketingListIds[3]) !== false);
            @$this->runControllerWithRedirectExceptionAndGetUrl($this->optOutUrl);
            $content    = $this->runControllerWithNoExceptionsAndGetContent($this->manageSubscriptionsUrl);
            $this->assertTrue(strpos($content, '<tr><td>marketingList 01</td>') !== false);
            $this->assertTrue(strpos($content, '<tr><td>marketingList 03</td>') !== false);
            $this->assertTrue(strpos($content, '<tr><td>marketingList 02</td>') === false);
            $this->assertTrue(strpos($content, '<tr><td>marketingList 04</td>') === false);
            $this->assertTrue(strpos($content, 'marketingLists/public/subscribe?hash=') !== false);
            $this->assertTrue(strpos($content, 'id="marketingListsManage' .
                                        'SubscriptionListView-toggleUnsubscribed_') !== false);
            $this->assertTrue(strpos($content, '%253D%253D" id="marketingListsManage' .
                                        'SubscriptionListView-toggleUnsubscribed_') !== false);
            $this->assertTrue(strpos($content, 'type="radio" name="marketingListsManage' .
                                        'SubscriptionListView-toggleUnsubscribed_') !== false);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                                        $marketingListIds[0] . '_1" checked="checked" type="radio" name="marketingListsManage' .
                                        'SubscriptionListView-toggleUnsubscribed_' . $marketingListIds[0]) !== false);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                                        $marketingListIds[2] . '_1" checked="checked" type="radio" name="marketingListsManage' .
                                        'SubscriptionListView-toggleUnsubscribed_' . $marketingListIds[2]) !== false);
            $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');
            $contact            = Contact::getByName('contact 05 contact 05son');
            $this->assertNotEmpty($contact);
            $contact            = $contact[0];
            $this->assertEquals(1, $contact->primaryEmail->optOut);
        }

        /**
         * @depends testOptOutAction
         */
        public function testSubscribeActionAfterOptOutActionDisableOptOut()
        {
            $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');
            $marketingList      = MarketingList::getByName('marketingList 04');
            $this->assertNotEmpty($marketingList);
            $marketingList      = $marketingList[0];
            $marketingListId    = $marketingList->id;
            $contact            = Contact::getByName('contact 05 contact 05son');
            $this->assertNotEmpty($contact);
            $contact            = $contact[0];
            $this->assertEquals(1, $contact->primaryEmail->optOut);
            $personId           = $contact->getClassId('Person');
            $member             = MarketingListMember::getByMarketingListIdContactIdAndSubscribed($marketingList->id,
                                                                                                    $contact->id,
                                                                                                    1);
            $this->assertNotEmpty($member);
            Yii::app()->user->userModel = null;
            $hash           = EmailMessageActivityUtil::resolveHashForFooter($personId, $marketingListId);
            $this->setGetArray(array(
                'hash'    => $hash,
            ));
            @$this->runControllerWithRedirectExceptionAndGetUrl($this->subscribeUrl);
            $content    = $this->runControllerWithNoExceptionsAndGetContent($this->manageSubscriptionsUrl);
            $this->assertTrue(strpos($content, '<tr><td>marketingList 01</td>') !== false);
            $this->assertTrue(strpos($content, '<tr><td>marketingList 03</td>') !== false);
            $this->assertTrue(strpos($content, 'marketingLists/public/subscribe?hash=') !== false);
            $this->assertTrue(strpos($content, 'id="marketingListsManage' .
                                'SubscriptionListView-toggleUnsubscribed_') !== false);
            $this->assertTrue(strpos($content, '%253D%253D" id="marketingListsManage' .
                                'SubscriptionListView-toggleUnsubscribed_') !== false);
            $this->assertTrue(strpos($content, 'type="radio" name="marketingListsManage' .
                                'SubscriptionListView-toggleUnsubscribed_') !== false);
            $this->assertTrue(strpos($content, 'id="marketingListsManageSubscriptionListView-toggleUnsubscribed_' .
                                $marketingListId. '_0" checked="checked" type="radio" name="marketingListsManage' .
                                'SubscriptionListView-toggleUnsubscribed_' . $marketingListId) !== false);
            $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');
            $contact            = Contact::getByName('contact 05 contact 05son');
            $this->assertNotEmpty($contact);
            $contact            = $contact[0];
            $this->assertEquals(0, $contact->primaryEmail->optOut);
        }
    }
?>