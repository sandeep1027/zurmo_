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

    class CreateOrUpdateExistingTableFromSchemaDefinitionArrayUtilTest extends BaseTest
    {
        protected static $messageLogger;

        public $freeze = false;

        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
            $super = User::getByUsername('super');
            Yii::app()->user->userModel = $super;
            static::$messageLogger = new MessageLogger();
        }

        public function setup()
        {
            parent::setUp();
            $freeze = false;
            if (RedBeanDatabase::isFrozen())
            {
                RedBeanDatabase::unfreeze();
                $freeze = true;
            }
            $this->freeze = $freeze;
        }

        public function tearDown()
        {
            if ($this->freeze)
            {
                RedBeanDatabase::freeze();
            }
            parent::teardown();
        }

        /**
         * @expectedException CException
         * @expectedMessage Invalid Schema definition received.
         */
        public function testGenerateOrUpdateTableBySchemaDefinitionWithEmptySchemaDefinition()
        {
            $schema = array();
            CreateOrUpdateExistingTableFromSchemaDefinitionArrayUtil::generateOrUpdateTableBySchemaDefinition($schema,
                                                                                                static::$messageLogger);

        }

        /**
         * @depends testGenerateOrUpdateTableBySchemaDefinitionWithEmptySchemaDefinition
         * @expectedException CException
         * @expectedMessage Invalid Schema definition received.
         */
        public function testGenerateOrUpdateTableBySchemaDefinitionWithNoTableName()
        {
            $schema     = array(array(
                'columns' => array(
                    array(
                        'name' => 'hash',
                        'type' => 'VARCHAR(32)',
                        'unsigned' => null,
                        'notNull' => 'NULL',
                        'collation' => 'COLLATE utf8_unicode_ci',
                        'default' => 'DEFAULT NULL',
                    ),
                ),
                'indexes' => array(),
                )
            );
            CreateOrUpdateExistingTableFromSchemaDefinitionArrayUtil::generateOrUpdateTableBySchemaDefinition($schema,
                                                                                                static::$messageLogger);
        }

        /**
         * @depends testGenerateOrUpdateTableBySchemaDefinitionWithNoTableName
         * @expectedException CException
         * @expectedMessage Invalid Schema definition received.
         */
        public function testGenerateOrUpdateTableBySchemaDefinitionWithTwoValuesInSchema()
        {
            $schema     = array('tableName' =>  array(
                                    'columns' => array(
                                        array(
                                            'name' => 'hash',
                                            'type' => 'VARCHAR(32)',
                                            'unsigned' => null,
                                            'notNull' => 'NULL',
                                            'collation' => 'COLLATE utf8_unicode_ci',
                                            'default' => 'DEFAULT NULL',
                                        ),
                                    ),
                                    'indexes' => array(),
                                ),
                                'tableName2' => array(
                                    'columns' => array(
                                        array(
                                            'name' => 'hash',
                                            'type' => 'VARCHAR(32)',
                                            'unsigned' => null,
                                            'notNull' => 'NULL',
                                            'collation' => 'COLLATE utf8_unicode_ci',
                                            'default' => 'DEFAULT NULL',
                                        ),
                                    ),
                                    'indexes' => array(),
                                )

                            );
            CreateOrUpdateExistingTableFromSchemaDefinitionArrayUtil::generateOrUpdateTableBySchemaDefinition($schema,
                                                                                                static::$messageLogger);
        }

        /**
         * @depends testGenerateOrUpdateTableBySchemaDefinitionWithTwoValuesInSchema
         * @expectedException CException
         * @expectedMessage Invalid Schema definition received.
         */
        public function testGenerateOrUpdateTableBySchemaDefinitionWithNoColumnsKey()
        {
            $schema     = array('tableName' =>  array(
                                        'indexes' => array(),
                                    ),
                                );
            CreateOrUpdateExistingTableFromSchemaDefinitionArrayUtil::generateOrUpdateTableBySchemaDefinition($schema,
                                                                                                static::$messageLogger);
        }

        /**
         * @depends testGenerateOrUpdateTableBySchemaDefinitionWithNoColumnsKey
         * @expectedException CException
         * @expectedMessage Invalid Schema definition received.
         */
        public function testGenerateOrUpdateTableBySchemaDefinitionWithNoColumns()
        {
            $schema     = array('tableName' =>  array(
                                        'columns'   => array(),
                                        'indexes'   => array(),
                                    ),
                                    );
            CreateOrUpdateExistingTableFromSchemaDefinitionArrayUtil::generateOrUpdateTableBySchemaDefinition($schema,
                                                                                                static::$messageLogger);
        }

        /**
         * @depends testGenerateOrUpdateTableBySchemaDefinitionWithNoColumns
         * @expectedException CException
         * @expectedMessage Invalid Schema definition received.
         */
        public function testGenerateOrUpdateTableBySchemaDefinitionWithNoIndexesKey()
        {
            $schema     = array('tableName' =>  array(
                'columns' => array(
                    array(
                        'name' => 'hash',
                        'type' => 'VARCHAR(32)',
                        'unsigned' => null,
                        'notNull' => 'NULL',
                        'collation' => 'COLLATE utf8_unicode_ci',
                        'default' => 'DEFAULT NULL',
                    ),
                ),
                ),
            );
            CreateOrUpdateExistingTableFromSchemaDefinitionArrayUtil::generateOrUpdateTableBySchemaDefinition($schema,
                                                                                                static::$messageLogger);
        }

        /**
         * @depends testGenerateOrUpdateTableBySchemaDefinitionWithNoIndexesKey
         */
        public function testGenerateOrUpdateTableBySchemaDefinitionWithNoIndexes()
        {
            $schema     = array('tableName1' => array(
                'columns' => array(
                    array(
                        'name' => 'hash',
                        'type' => 'VARCHAR(32)',
                        'unsigned' => null,
                        'notNull' => 'NULL',
                        'collation' => 'COLLATE utf8_unicode_ci',
                        'default' => 'DEFAULT NULL',
                    ),
                ),
                'indexes' => array(),
            ),
            );
            CreateOrUpdateExistingTableFromSchemaDefinitionArrayUtil::generateOrUpdateTableBySchemaDefinition($schema,
                                                                                                static::$messageLogger);
            $processedTables    = CreateOrUpdateExistingTableFromSchemaDefinitionArrayUtil::resolveProcessedTables();
            $this->assertNotEmpty($processedTables);
            $this->assertCount(1, $processedTables);
            $this->assertEquals('tableName1', $processedTables[0]);
        }

        /**
         * @depends testGenerateOrUpdateTableBySchemaDefinitionWithNoIndexes
         * @expectedException CException
         * @expectedMessage Invalid Schema definition received.
         */
        public function testGenerateOrUpdateTableBySchemaDefinitionWithColumnsMissingKeys()
        {
            $schema     = array('tableName2' => array(
                'columns' => array(
                    array(
                        'name' => 'hash',
                        'type' => 'VARCHAR(32)',
                        'collation' => 'COLLATE utf8_unicode_ci',
                        'default' => 'DEFAULT NULL',
                    ),
                ),
                'indexes' => array(),
            ),
            );
            CreateOrUpdateExistingTableFromSchemaDefinitionArrayUtil::generateOrUpdateTableBySchemaDefinition($schema,
                                                                                                static::$messageLogger);
        }

        /**
         * @depends testGenerateOrUpdateTableBySchemaDefinitionWithColumnsMissingKeys
         * @expectedException CException
         * @expectedMessage Invalid Schema definition received.
         */
        public function testGenerateOrUpdateTableBySchemaDefinitionWithColumnsHavingExtraKeys()
        {
            $schema     = array('tableName2' => array(
                'columns' => array(
                    array(
                        'name' => 'hash',
                        'type' => 'VARCHAR(32)',
                        'unsigned' => null,
                        'NOTNULLHERE' => 'NULL',
                        'collation' => 'COLLATE utf8_unicode_ci',
                        'default' => 'DEFAULT NULL',
                    ),
                ),
                'indexes' => array(),
            ),
            );
            CreateOrUpdateExistingTableFromSchemaDefinitionArrayUtil::generateOrUpdateTableBySchemaDefinition($schema,
                                                                                            static::$messageLogger);
        }

        /**
         * @depends testGenerateOrUpdateTableBySchemaDefinitionWithColumnsHavingExtraKeys
         * @expectedException CException
         * @expectedMessage Invalid Schema definition received.
         */
        public function testGenerateOrUpdateTableBySchemaDefinitionWithIndexesHavingIntegerKeys()
        {
            $schema     = array('tableName2' => array(
                'columns' => array(
                    array(
                        'name' => 'hash',
                        'type' => 'VARCHAR(32)',
                        'unsigned' => null,
                        'notNull' => 'NULL',
                        'collation' => 'COLLATE utf8_unicode_ci',
                        'default' => 'DEFAULT NULL',
                    ),
                ),
                'indexes' => array(
                                array(
                                    'columns' => array('hash'),
                                    'unique' => false
                                )
                ),
            ),
            );
            CreateOrUpdateExistingTableFromSchemaDefinitionArrayUtil::generateOrUpdateTableBySchemaDefinition($schema,
                                                                                                static::$messageLogger);
        }

        /**
         * @depends testGenerateOrUpdateTableBySchemaDefinitionWithIndexesHavingIntegerKeys
         * @expectedException CException
         * @expectedMessage Invalid Schema definition received.
         */
        public function testGenerateOrUpdateTableBySchemaDefinitionWithIndexesHavingMoreThanTwoItems()
        {
            $schema     = array('tableName2' => array(
                'columns' => array(
                    array(
                        'name' => 'hash',
                        'type' => 'VARCHAR(32)',
                        'unsigned' => null,
                        'notNull' => 'NULL',
                        'collation' => 'COLLATE utf8_unicode_ci',
                        'default' => 'DEFAULT NULL',
                    ),
                ),
                'indexes' => array(
                    'indexName' => array(
                        'columns' => array('hash'),
                        'unique' => false,
                        'third' => 1,
                    )
                ),
            ),
            );
            CreateOrUpdateExistingTableFromSchemaDefinitionArrayUtil::generateOrUpdateTableBySchemaDefinition($schema,
                                                                                                static::$messageLogger);
        }

        /**
         * @depends testGenerateOrUpdateTableBySchemaDefinitionWithIndexesHavingMoreThanTwoItems
         * @expectedException CException
         * @expectedMessage Invalid Schema definition received.
         */
        public function testGenerateOrUpdateTableBySchemaDefinitionWithIndexesHavingNoColumnsKey()
        {
            $schema     = array('tableName2' => array(
                'columns' => array(
                    array(
                        'name' => 'hash',
                        'type' => 'VARCHAR(32)',
                        'unsigned' => null,
                        'notNull' => 'NULL',
                        'collation' => 'COLLATE utf8_unicode_ci',
                        'default' => 'DEFAULT NULL',
                    ),
                ),
                'indexes' => array(
                    'indexName' => array(
                        'unique' => false,
                        'third' => 1,
                    )
                ),
            ),
            );
            CreateOrUpdateExistingTableFromSchemaDefinitionArrayUtil::generateOrUpdateTableBySchemaDefinition($schema,
                                                                                                static::$messageLogger);

        }

        /**
         * @depends testGenerateOrUpdateTableBySchemaDefinitionWithIndexesHavingNoColumnsKey
         * @expectedException CException
         * @expectedMessage Invalid Schema definition received.
         */
        public function testGenerateOrUpdateTableBySchemaDefinitionWithUIndexesHavingNoUniqueKey()
        {
            $schema     = array('tableName2' => array(
                'columns' => array(
                    array(
                        'name' => 'hash',
                        'type' => 'VARCHAR(32)',
                        'unsigned' => null,
                        'notNull' => 'NULL',
                        'collation' => 'COLLATE utf8_unicode_ci',
                        'default' => 'DEFAULT NULL',
                    ),
                ),
                'indexes' => array(
                    'indexName' => array(
                        'unique' => false,
                        'third' => 1,
                    )
                ),
            ),
            );
            CreateOrUpdateExistingTableFromSchemaDefinitionArrayUtil::generateOrUpdateTableBySchemaDefinition($schema,
                                                                                                static::$messageLogger);
        }

        /**
         * @depends testGenerateOrUpdateTableBySchemaDefinitionWithUIndexesHavingNoUniqueKey
         * @expectedException CException
         * @expectedMessage Invalid Schema definition received.
         */
        public function testGenerateOrUpdateTableBySchemaDefinitionWithIndexColumnKeyNotBeingArray()
        {
            $schema     = array('tableName2' => array(
                'columns' => array(
                    array(
                        'name' => 'hash',
                        'type' => 'VARCHAR(32)',
                        'unsigned' => null,
                        'notNull' => 'NULL',
                        'collation' => 'COLLATE utf8_unicode_ci',
                        'default' => 'DEFAULT NULL',
                    ),
                ),
                'indexes' => array(
                    'indexName' => array(
                        'columns' => 'hash',
                        'unique' => true,
                    )
                ),
            ),
            );
            CreateOrUpdateExistingTableFromSchemaDefinitionArrayUtil::generateOrUpdateTableBySchemaDefinition($schema,
                                                                                                static::$messageLogger);
        }

        /**
         * @depends testGenerateOrUpdateTableBySchemaDefinitionWithIndexColumnKeyNotBeingArray
         * @expectedException CException
         * @expectedMessage Invalid Schema definition received.
         */
        public function testGenerateOrUpdateTableBySchemaDefinitionWithIndexColumnNotFound()
        {
            $schema     = array('tableName2' => array(
                'columns' => array(
                    array(
                        'name' => 'hash',
                        'type' => 'VARCHAR(32)',
                        'unsigned' => null,
                        'notNull' => 'NULL',
                        'collation' => 'COLLATE utf8_unicode_ci',
                        'default' => 'DEFAULT NULL',
                    ),
                ),
                'indexes' => array(
                    'indexName' => array(
                        'columns'   => array('hasha'),
                        'unique' => false,
                    )
                ),
            ),
            );
            CreateOrUpdateExistingTableFromSchemaDefinitionArrayUtil::generateOrUpdateTableBySchemaDefinition($schema,
                                                                                                static::$messageLogger);
        }

        /**
         * @depends testGenerateOrUpdateTableBySchemaDefinitionWithIndexColumnNotFound
         */
        public function testGenerateOrUpdateTableBySchemaDefinitionWithValidSchema()
        {
            $schema     = array('tableName3' => array(
                'columns' => array(
                    array(
                        'name' => 'hash',
                        'type' => 'VARCHAR(32)',
                        'unsigned' => null,
                        'notNull' => 'NULL',
                        'collation' => 'COLLATE utf8_unicode_ci',
                        'default' => 'DEFAULT NULL',
                    ),
                    array(
                        'name' => 'language',
                        'type' => 'VARCHAR(10)',
                        'unsigned' => null,
                        'notNull' => 'NULL',
                        'collation' => 'COLLATE utf8_unicode_ci',
                        'default' => 'DEFAULT NULL',
                    ),
                    array(
                        'name' => 'locale',
                        'type' => 'VARCHAR(10)',
                        'unsigned' => null,
                        'notNull' => 'NULL',
                        'collation' => 'COLLATE utf8_unicode_ci',
                        'default' => 'DEFAULT NULL',
                    ),
                    array(
                        'name' => 'timezone',
                        'type' => 'VARCHAR(64)',
                        'unsigned' => null,
                        'notNull' => 'NULL',
                        'collation' => 'COLLATE utf8_unicode_ci',
                        'default' => 'DEFAULT NULL',
                    ),
                    array(
                        'name' => 'username',
                        'type' => 'VARCHAR(64)',
                        'unsigned' => null,
                        'notNull' => 'NULL',
                        'collation' => 'COLLATE utf8_unicode_ci',
                        'default' => 'DEFAULT NULL',
                    ),
                    array(
                        'name' => 'serializedavatardata',
                        'type' => 'TEXT',
                        'unsigned' => null,
                        'notNull' => 'NULL',
                        'collation' => 'COLLATE utf8_unicode_ci',
                        'default' => 'DEFAULT NULL',
                    ),
                    array(
                        'name' => 'isactive',
                        'type' => 'TINYINT(1) UNSIGNED',
                        'unsigned' => null,
                        'notNull' => 'NULL',
                        'collation' => null,
                        'default' => 'DEFAULT NULL',
                    ),
                    array(
                        'name' => 'lastlogindatetime',
                        'type' => 'DATETIME',
                        'unsigned' => null,
                        'notNull' => 'NULL',
                        'collation' => null,
                        'default' => 'DEFAULT NULL',
                    ),
                    array(
                        'name' => 'permitable_id',
                        'type' => 'INT(11)',
                        'unsigned' => 'UNSIGNED',
                        'notNull' => 'NULL',
                        'collation' => null,
                        'default' => 'DEFAULT NULL',
                    ),
                    array(
                        'name' => 'person_id',
                        'type' => 'INT(11)',
                        'unsigned' => 'UNSIGNED',
                        'notNull' => 'NULL',
                        'collation' => null,
                        'default' => 'DEFAULT NULL',
                    ),
                    array(
                        'name' => 'currency_id',
                        'type' => 'INT(11)',
                        'unsigned' => 'UNSIGNED',
                        'notNull' => 'NULL',
                        'collation' => null,
                        'default' => 'DEFAULT NULL',
                    ),
                    array(
                        'name' => 'manager__user_id',
                        'type' => 'INT(11)',
                        'unsigned' => 'UNSIGNED',
                        'notNull' => 'NULL',
                        'collation' => null,
                        'default' => 'DEFAULT NULL',
                    ),
                    array(
                        'name' => 'role_id',
                        'type' => 'INT(11)',
                        'unsigned' => 'UNSIGNED',
                        'notNull' => 'NULL',
                        'collation' => null,
                        'default' => 'DEFAULT NULL',
                    ),
                ),
                'indexes' => array(
                    'unique_username_Index' => array(
                        'columns' => array('username'),
                        'unique' => true
                    )
                )
            )
            );
            CreateOrUpdateExistingTableFromSchemaDefinitionArrayUtil::generateOrUpdateTableBySchemaDefinition($schema,
                                                                                                static::$messageLogger);
            $processedTables    = CreateOrUpdateExistingTableFromSchemaDefinitionArrayUtil::resolveProcessedTables();
            $this->assertNotEmpty($processedTables);
            $this->assertCount(2, $processedTables);
            $this->assertEquals('tableName3', $processedTables[1]);
        }

        /**
         * @depends testGenerateOrUpdateTableBySchemaDefinitionWithValidSchema
         */
        public function testGenerateOrUpdateTableBySchemaDefinitionWithValidButChangedSchemaForExistingTableWithIsFreshInstall()
        {
            Yii::app()->params['isFreshInstall'] = true;
            $schema     = array('tableName3' => array(
                'columns' => array(
                    array(
                        'name' => 'hash',
                        'type' => 'VARCHAR(64)',
                        'unsigned' => null,
                        'notNull' => 'NOT NULL',
                        'collation' => 'COLLATE utf8_unicode_ci',
                        'default' => 'DEFAULT "bacdefghi"',
                    ),
                    array(
                        'name' => 'newlanguage',
                        'type' => 'VARCHAR(100)',
                        'unsigned' => null,
                        'notNull' => 'NOT NULL',
                        'collation' => 'COLLATE utf8_unicode_ci',
                        'default' => 'DEFAULT "1234567"',
                    ),
                    array(
                        'name' => 'locale',
                        'type' => 'VARCHAR(100)',
                        'unsigned' => null,
                        'notNull' => 'NULL',
                        'collation' => 'COLLATE utf8_unicode_ci',
                        'default' => 'DEFAULT NULL',
                    ),
                    array(
                        'name' => 'timezone',
                        'type' => 'VARCHAR(32)',
                        'unsigned' => null,
                        'notNull' => 'NOT NULL',
                        'collation' => 'COLLATE utf8_general_ci',
                        'default' => 'DEFAULT "abc/def"',
                    ),
                    array(
                        'name' => 'username',
                        'type' => 'VARCHAR(10)',
                        'unsigned' => null,
                        'notNull' => 'NOT NULL',
                        'collation' => 'COLLATE utf8_unicode_ci',
                        'default' => 'DEFAULT "superman"',
                    ),
                    array(
                        'name' => 'serializedavatardata',
                        'type' => 'TEXT',
                        'unsigned' => null,
                        'notNull' => 'NULL',
                        'collation' => 'COLLATE utf8_unicode_ci',
                        'default' => 'DEFAULT NULL',
                    ),
                    array(
                        'name' => 'isactive',
                        'type' => 'TINYINT(1) UNSIGNED',
                        'unsigned' => null,
                        'notNull' => 'NULL',
                        'collation' => null,
                        'default' => 'DEFAULT NULL',
                    ),
                    array(
                        'name' => 'permitable_id',
                        'type' => 'INT(11)',
                        'unsigned' => 'UNSIGNED',
                        'notNull' => 'NULL',
                        'collation' => null,
                        'default' => 'DEFAULT NULL',
                    ),
                    array(
                        'name' => 'role_id',
                        'type' => 'INT(11)',
                        'unsigned' => 'UNSIGNED',
                        'notNull' => 'NULL',
                        'collation' => null,
                        'default' => 'DEFAULT NULL',
                    ),
                ),
                'indexes' => array(
                    'unique_username_Index' => array(
                        'columns' => array('username'),
                        'unique' => true
                    ),
                    'unique_isactive_Index' => array(
                        'columns' => array('username'),
                        'unique' => true
                    ),
                    'role_id_Index' => array(
                        'columns'   => array('role_id'),
                        'unique'    => false,
                    ),
                )
            )
            );
            CreateOrUpdateExistingTableFromSchemaDefinitionArrayUtil::generateOrUpdateTableBySchemaDefinition($schema,
                                                                                                static::$messageLogger);
            $processedTables    = CreateOrUpdateExistingTableFromSchemaDefinitionArrayUtil::resolveProcessedTables();
            $this->assertNotEmpty($processedTables);
            $this->assertCount(2, $processedTables);
            $this->assertEquals('tableName3', $processedTables[1]);
        }

        /**
         * @depends testGenerateOrUpdateTableBySchemaDefinitionWithValidButChangedSchemaForExistingTableWithIsFreshInstall
         */
        public function testGenerateOrUpdateTableBySchemaDefinitionWithValidButChangedSchemaForExistingTableWithNoIsFreshInstall()
        {
            echo PHP_EOL . PHP_EOL;
            var_dump(__CLASS__ . '.' . __FUNCTION__ . '.' . __LINE__ . PHP_EOL);
            Yii::app()->params['isFreshInstall'] = false;
            $schema     = array('tableName3' => array(
                'columns' => array(
                    array(
                        'name' => 'hash',
                        'type' => 'VARCHAR(64)',
                        'unsigned' => null,
                        'notNull' => 'NOT NULL',
                        'collation' => 'COLLATE utf8_unicode_ci',
                        'default' => 'DEFAULT "bacdefghi"',
                    ),
                    array(
                        'name' => 'newlanguage',
                        'type' => 'VARCHAR(100)',
                        'unsigned' => null,
                        'notNull' => 'NOT NULL',
                        'collation' => 'COLLATE utf8_unicode_ci',
                        'default' => 'DEFAULT "1234567"',
                    ),
                    array(
                        'name' => 'locale',
                        'type' => 'VARCHAR(100)',
                        'unsigned' => null,
                        'notNull' => 'NULL',
                        'collation' => 'COLLATE utf8_unicode_ci',
                        'default' => 'DEFAULT NULL',
                    ),
                    array(
                        'name' => 'timezone',
                        'type' => 'VARCHAR(32)',
                        'unsigned' => null,
                        'notNull' => 'NOT NULL',
                        'collation' => 'COLLATE utf8_general_ci',
                        'default' => 'DEFAULT "abc/def"',
                    ),
                    array(
                        'name' => 'username',
                        'type' => 'VARCHAR(10)',
                        'unsigned' => null,
                        'notNull' => 'NOT NULL',
                        'collation' => 'COLLATE utf8_unicode_ci',
                        'default' => 'DEFAULT "superman"',
                    ),
                    array(
                        'name' => 'serializedavatardata',
                        'type' => 'TEXT',
                        'unsigned' => null,
                        'notNull' => 'NULL',
                        'collation' => 'COLLATE utf8_unicode_ci',
                        'default' => 'DEFAULT NULL',
                    ),
                    array(
                        'name' => 'isactive',
                        'type' => 'TINYINT(1) UNSIGNED',
                        'unsigned' => null,
                        'notNull' => 'NULL',
                        'collation' => null,
                        'default' => 'DEFAULT NULL',
                    ),
                    array(
                        'name' => 'permitable_id',
                        'type' => 'INT(11)',
                        'unsigned' => 'UNSIGNED',
                        'notNull' => 'NULL',
                        'collation' => null,
                        'default' => 'DEFAULT NULL',
                    ),
                    array(
                        'name' => 'role_id',
                        'type' => 'INT(11)',
                        'unsigned' => 'UNSIGNED',
                        'notNull' => 'NULL',
                        'collation' => null,
                        'default' => 'DEFAULT NULL',
                    ),
                ),
                'indexes' => array(
                    'unique_username_Index' => array(
                        'columns' => array('username'),
                        'unique' => true
                    ),
                    'unique_isactive_Index' => array(
                        'columns' => array('username'),
                        'unique' => true
                    ),
                    'role_id_Index' => array(
                        'columns'   => array('role_id'),
                        'unique'    => false,
                    ),
                )
            )
            );
            CreateOrUpdateExistingTableFromSchemaDefinitionArrayUtil::generateOrUpdateTableBySchemaDefinition($schema,
                                                                                                static::$messageLogger);
            $processedTables    = CreateOrUpdateExistingTableFromSchemaDefinitionArrayUtil::resolveProcessedTables();
            $this->assertNotEmpty($processedTables);
            $this->assertCount(2, $processedTables);
            $this->assertEquals('tableName3', $processedTables[1]);
            // ensure column types, other properties have changed, no columns have been added, old haven't been deleted
            // missing indexes are added.
            // valid but different schema, same table, ensure types have changed to new ones
            // valid schema but new indexes, ensure new indexes have been added
            // valid schema but missing few columns, ensure they aren't dropped
            // try with and without isFreshInstall
        }
    }
?>