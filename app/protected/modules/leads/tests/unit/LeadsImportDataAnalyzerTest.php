<?php
    /*********************************************************************************
     * Zurmo is a customer relationship management program developed by
     * Zurmo, Inc. Copyright (C) 2013 Zurmo Inc.
     *
     * Zurmo is free software; you can redistribute it and/or modify it under
     * the terms of the GNU Affero General Public License version 3 as published by the
     * Free Software Foundation with the addition of the following permission added
     * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
     * IN WHICH THE COPYRIGHT IS OWNED BY ZURMO, ZURMO DISCLAIMS THE WARRANTY
     * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
     *
     * Zurmo is distributed in the hope that it will be useful, but WITHOUT
     * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
     * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
     * details.
     *
     * You should have received a copy of the GNU Affero General Public License along with
     * this program; if not, see http://www.gnu.org/licenses or write to the Free
     * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
     * 02110-1301 USA.
     *
     * You can contact Zurmo, Inc. with a mailing address at 27 North Wacker Drive
     * Suite 370 Chicago, IL 60606. or at email address contact@zurmo.com.
     *
     * The interactive user interfaces in original and modified versions
     * of this program must display Appropriate Legal Notices, as required under
     * Section 5 of the GNU Affero General Public License version 3.
     *
     * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
     * these Appropriate Legal Notices must retain the display of the Zurmo
     * logo and Zurmo copyright notice. If the display of the logo is not reasonably
     * feasible for technical reasons, the Appropriate Legal Notices must display the words
     * "Copyright Zurmo Inc. 2013. All rights reserved".
     ********************************************************************************/

    class LeadsImportDataAnalyzerTest extends ImportBaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            $super = SecurityTestHelper::createSuperAdmin();
            Yii::app()->user->userModel = $super;
            ContactsModule::loadStartingData();
        }

        public function testImportDataAnalysisResults()
        {
            $super                             = User::getByUsername('super');
            Yii::app()->user->userModel        = $super;
            $import                            = new Import();
            $serializedData['importRulesType'] = 'Leads';
            $import->serializedData            = serialize($serializedData);
            $this->assertTrue($import->save());

            ImportTestHelper::
            createTempTableByFileNameAndTableName('importAnalyzerTest.csv', $import->getTempTableName(),
                                                  Yii::getPathOfAlias('application.modules.leads.tests.unit.files'));
            $mappingData = array(
                'column_0'  => array('attributeIndexOrDerivedType' => 'LeadState',
                                     'type' => 'importColumn',
                                      'mappingRulesData' => array(
                                          'DefaultLeadStateIdMappingRuleForm' =>
                                          array('defaultStateId' => null))),
            );
            $serializedData                = unserialize($import->serializedData);
            $serializedData['mappingData'] = $mappingData;
            $import->serializedData        = serialize($serializedData);
            $this->assertTrue($import->save());

            $importRules  = ImportRulesUtil::makeImportRulesByType('Leads');
            $config       = array('pagination' => array('pageSize' => 2));
            //This test csv has a header row.
            $dataProvider = new ImportDataProvider($import->getTempTableName(), true, $config);

            //Run data analyzer
            $importDataAnalyzer = new ImportDataAnalyzer($importRules, $dataProvider);
            foreach ($mappingData as $columnName => $columnMappingData)
            {
                $importDataAnalyzer->analyzeByColumnNameAndColumnMappingData($columnName, $columnMappingData);
            }
            $messagesData = $importDataAnalyzer->getMessagesData();
            $compareData = array(
                'column_0' => array(
                    array('message' => '3 value(s) are not valid. Rows that have these values will be skipped during import.',
                          'sanitizerUtilType' => 'LeadState', 'moreAvailable' => false),
                ),
            );
            $this->assertEquals($compareData, $messagesData);
            $importInstructionsData   = $importDataAnalyzer->getImportInstructionsData();
            $compareInstructionsData  = array();
            $this->assertEquals($compareInstructionsData, $importInstructionsData);
        }

            /**
         * @depends testImportDataAnalysisResults
         */
        public function testImportDataAnalysisUsingBatchAnalyzers()
        {
            Yii::app()->user->userModel        = User::getByUsername('super');

            $import                            = new Import();
            $serializedData['importRulesType'] = 'ImportModelTestItem';
            $import->serializedData            = serialize($serializedData);
            $this->assertTrue($import->save());
            ImportTestHelper::
            createTempTableByFileNameAndTableName('importAnalyzerTest.csv', $import->getTempTableName(),
                                                  Yii::getPathOfAlias('application.modules.leads.tests.unit.files'));

            $config       = array('pagination' => array('pageSize' => 2));
            $dataProvider = new ImportDataProvider($import->getTempTableName(), true, $config);

            //Test contact state sanitization by batch.
            $dataAnalyzer = new LeadStateBatchAttributeValueDataAnalyzer('Leads', null);
            $dataAnalyzer->runAndMakeMessages($dataProvider, 'column_0');
            $messages = $dataAnalyzer->getMessages();
            $this->assertEquals(1, count($messages));
            $compareMessage = '3 value(s) are not valid. Rows that have these values will be skipped during import.';
            $this->assertEquals($compareMessage, $messages[0]);
        }
    }
?>
