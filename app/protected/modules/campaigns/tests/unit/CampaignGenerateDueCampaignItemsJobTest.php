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
    class CampaignGenerateDueCampaignItemsJobTest extends ZurmoBaseTest
    {
        protected $user;

        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
        }

        public function setUp()
        {
            parent::setUp();
            $this->user                 = User::getByUsername('super');
            Yii::app()->user->userModel = $this->user;
        }

        public function testGetDisplayName()
        {
            $displayName                = CampaignGenerateDueCampaignItemsJob::getDisplayName();
            $this->assertEquals('Generate campaign items', $displayName);
        }

        public function testGetType()
        {
            $type                       = CampaignGenerateDueCampaignItemsJob::getType();
            $this->assertEquals('CampaignGenerateDueCampaignItems', $type);
        }

        public function testGetRecommendedRunFrequencyContent()
        {
            $recommendedRunFrequency    = CampaignGenerateDueCampaignItemsJob::getRecommendedRunFrequencyContent();
            $this->assertEquals('Every hour', $recommendedRunFrequency);
        }

        public function testRunWithoutAnyCampaigns()
        {
            $campaigns              = Campaign::getAll();
            $this->assertEmpty($campaigns);
            $job                    = new CampaignGenerateDueCampaignItemsJob();
            $this->assertTrue($job->run());
        }

        /**
         * @depends testRunWithoutAnyCampaigns
         */
        public function testRunWithDueButNoActiveCampaigns()
        {
            $marketingList          = MarketingListTestHelper::populateMarketingListByName('marketingList 01');
            CampaignTestHelper::createCampaign('Processing',
                                                'subject',
                                                'text Content',
                                                'Html Content',
                                                null,
                                                null,
                                                null,
                                                Campaign::TYPE_MARKETING_LIST,
                                                Campaign::STATUS_PROCESSING,
                                                null,
                                                null,
                                                null,
                                                $marketingList);
            CampaignTestHelper::createCampaign('Incomplete',
                                                'subject',
                                                'text Content',
                                                'Html Content',
                                                null,
                                                null,
                                                null,
                                                Campaign::TYPE_MARKETING_LIST,
                                                Campaign::STATUS_INCOMPLETE,
                                                null,
                                                null,
                                                null,
                                                $marketingList);
            CampaignTestHelper::createCampaign('Paused',
                                                'subject',
                                                'text Content',
                                                'Html Content',
                                                null,
                                                null,
                                                null,
                                                Campaign::TYPE_MARKETING_LIST,
                                                Campaign::STATUS_PAUSED,
                                                null,
                                                null,
                                                null,
                                                $marketingList);
            $this->assertEmpty(CampaignItem::getAll());
            $job                    = new CampaignGenerateDueCampaignItemsJob();
            $this->assertTrue($job->run());
            $this->assertEmpty(CampaignItem::getAll());
        }

        /**
         * @depends testRunWithDueButNoActiveCampaigns
         */
        public function testRunWithNonDueActiveCampaigns()
        {
            $marketingList              = MarketingListTestHelper::populateMarketingListByName('marketingList 02');
            $tenDaysFromNowTimestamp    = time() + 60*60*24*10;
            $tenDaysFromNowDateTime     = DateTimeUtil::convertTimestampToDbFormatDateTime($tenDaysFromNowTimestamp);
            CampaignTestHelper::createCampaign('Active And Non Due',
                                                'subject',
                                                'text Content',
                                                'Html Content',
                                                null,
                                                null,
                                                null,
                                                Campaign::TYPE_MARKETING_LIST,
                                                Campaign::STATUS_ACTIVE,
                                                null,
                                                $tenDaysFromNowDateTime,
                                                null,
                                                $marketingList);
            $this->assertEmpty(CampaignItem::getAll());
            $job                    = new CampaignGenerateDueCampaignItemsJob();
            $this->assertTrue($job->run());
            $this->assertEmpty(CampaignItem::getAll());
        }

        /**
         * @depends testRunWithNonDueActiveCampaigns
         */
        public function testRunWithDueActiveCampaignsWithNonMembers()
        {
            $marketingList              = MarketingListTestHelper::populateMarketingListByName('marketingList 03');
            CampaignTestHelper::createCampaign('Active, Due But No Members',
                                                'subject',
                                                'text Content',
                                                'Html Content',
                                                null,
                                                null,
                                                null,
                                                Campaign::TYPE_MARKETING_LIST,
                                                Campaign::STATUS_ACTIVE,
                                                null,
                                                null,
                                                null,
                                                $marketingList);
            $this->assertEmpty(CampaignItem::getAll());
            $job                    = new CampaignGenerateDueCampaignItemsJob();
            $this->assertTrue($job->run());
            $this->assertEmpty(CampaignItem::getAll());
        }

        /**
         * @depends testRunWithDueActiveCampaignsWithNonMembers
         */
        public function testRunWithDueActiveCampaignsWithMembers()
        {
            $marketingList      = MarketingListTestHelper::createMarketingListByName('marketingList 04');
            $marketingListId    = $marketingList->id;
            $contact1           = ContactTestHelper::createContactByNameForOwner('campaignContact 01', $this->user);
            $contact2           = ContactTestHelper::createContactByNameForOwner('campaignContact 02', $this->user);
            $contact3           = ContactTestHelper::createContactByNameForOwner('campaignContact 03', $this->user);
            $contact4           = ContactTestHelper::createContactByNameForOwner('campaignContact 04', $this->user);
            $contact5           = ContactTestHelper::createContactByNameForOwner('campaignContact 05', $this->user);
            $processed          = 0;
            MarketingListMemberTestHelper::createMarketingListMember($processed, $marketingList, $contact1);
            MarketingListMemberTestHelper::createMarketingListMember($processed, $marketingList, $contact2);
            MarketingListMemberTestHelper::createMarketingListMember($processed, $marketingList, $contact3);
            MarketingListMemberTestHelper::createMarketingListMember($processed, $marketingList, $contact4);
            MarketingListMemberTestHelper::createMarketingListMember($processed, $marketingList, $contact5);
            $marketingList->forgetAll();

            $marketingList      = MarketingList::getById($marketingListId);
            CampaignTestHelper::createCampaign('Active, Due With Members',
                                                'subject',
                                                'text Content',
                                                'Html Content',
                                                null,
                                                null,
                                                null,
                                                Campaign::TYPE_MARKETING_LIST,
                                                Campaign::STATUS_ACTIVE,
                                                null,
                                                null,
                                                null,
                                                $marketingList);
            $this->assertEmpty(CampaignItem::getAll());
            $job                = new CampaignGenerateDueCampaignItemsJob();
            $this->assertTrue($job->run());
            $campaign           = Campaign::getByName('Active, Due With Members');
            $this->assertEquals(Campaign::STATUS_PROCESSING, $campaign[0]->status);
            $allCampaignItems   = CampaignItem::getAll();
            $this->assertNotEmpty(CampaignItem::getAll());
            $this->assertCount(5, $allCampaignItems);
            $campaignItems      = CampaignItem::getByProcessedAndCampaignId(0, $campaign[0]->id);
            $this->assertNotEmpty($campaignItems);
            $this->assertCount(5, $campaignItems);
        }
    }
?>