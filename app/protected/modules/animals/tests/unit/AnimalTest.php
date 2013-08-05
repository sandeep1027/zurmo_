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

    class AnimalTest extends ZurmoBaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
            SecurityTestHelper::createUsers();
        }

        public function setUp()
        {
            parent::setUp();
            Yii::app()->user->userModel = User::getByUsername('super');
        }

        
        public function testCreateAndGetAnimalById()
        {
            $user = UserTestHelper::createBasicUser('Steven');
               
            $animal = new Animal();
            $animal->owner       = $user;
            $animal->name        = DataUtil::purifyHtml("Tom & Jerry's Animal");
            $this->assertEquals("Tom & Jerry's Animal", $animal->name);

            $saved=$animal->save();

            $this->assertTrue($saved);
            $id = $animal->id;
            unset($animal);
            $animal = Animal::getById($id);
            $this->assertEquals("Tom & Jerry's Animal", $animal->name);
            $animal->name        = 'Windsor Wallaby';
            $animal->binomialName->value = 'Phascolarctos cinereus';
            $this->assertTrue($animal->save());
            $id = $animal->id;
            unset($animal);
            $animal = Animal::getById($id);
            $this->assertEquals('Windsor Wallaby', $animal->name);
            $this->assertEquals('Phascolarctos cinereus',   $animal->binomialName->value);
        }

        

        /**
         * @depends testCreateAndGetAnimalById
         */
        public function testGetAnimalsByName()
        {
            $animals = Animal::getByName('Windsor Wallaby');
            $this->assertEquals(1, count($animals));
            $this->assertEquals('Windsor Wallaby', $animals[0]->name);
        }

        /**
         * @depends testCreateAndGetAnimalById
         */
        public function testGetLabel()
        {
            $animals = Animal::getByName('Windsor Wallaby');
            $this->assertEquals(1, count($animals));
            $this->assertEquals('Animal',  $animals[0]::getModelLabelByTypeAndLanguage('Singular'));
            $this->assertEquals('Animals', $animals[0]::getModelLabelByTypeAndLanguage('Plural'));
        }

        /**
         * @depends testGetAnimalsByName
         */
        public function testGetAnimalsByNameForNonExistentName()
        {
            $animals = Animal::getByName('Windsor Wallaby 69');
            $this->assertEquals(0, count($animals));
        }

        /**
         * @depends testCreateAndGetAnimalById
         */
        public function testGetAll()
        {
            $user = User::getByUsername('steven');

            $animal = new Animal();
            $animal->owner = $user;
            $animal->name  = 'Windsor Wallaby 2';
            $this->assertTrue($animal->save());
            $animals = Animal::getAll();
            $this->assertEquals(2, count($animals));
            $this->assertTrue('Windsor Wallaby'   == $animals[0]->name &&
                              'Windsor Wallaby 2' == $animals[1]->name ||
                              'Windsor Wallaby 2' == $animals[0]->name &&
                              'Windsor Wallaby'   == $animals[1]->name);
        }

        /**
         * @depends testCreateAndGetAnimalById
         */
        public function testSetAndGetOwner()
        {
            $user = UserTestHelper::createBasicUser('Dicky');

            $animals = Animal::getByName('Windsor Wallaby');
            $this->assertEquals(1, count($animals));
            $animal = $animals[0];
            $animal->owner = $user;
            $saved = $animal->save();
            $this->assertTrue($saved);
            unset($user);
            $this->assertTrue($animal->owner->id > 0);
            $user = $animal->owner;
            $animal->owner = null;
            $this->assertNotNull($animal->owner);
            $this->assertFalse($animal->validate());
            $animal->forget();
        }

        /**
         * @depends testSetAndGetOwner
         */
        public function testReplaceOwner()
        {
            $animals = Animal::getByName('Windsor Wallaby');
            $this->assertEquals(1, count($animals));
            $animal = $animals[0];
            $user = User::getByUsername('dicky');
            $this->assertEquals($user->id, $animal->owner->id);
            unset($user);
            $animal->owner = User::getByUsername('benny');
            $this->assertTrue($animal->owner !== null);
            $user = $animal->owner;
            $this->assertEquals('benny', $user->username);
            unset($user);
        }

        /**
         * @depends testCreateAndGetAnimalById
         */
        public function testUpdateAnimalFromForm()
        {
            $animals = Animal::getByName('Windsor Wallaby');
            $animal = $animals[0];
            $this->assertEquals($animal->name, 'Windsor Wallaby');
            $postData = array('name' => 'New Name');
            $animal->setAttributes($postData);
            $this->assertTrue($animal->save());

            $id = $animal->id;
            unset($animal);
            $animal = Animal::getById($id);
            $this->assertEquals('New Name', $animal->name);
        }

        

        /**
         * @depends testUpdateAnimalFromForm
         */
        public function testDeleteAnimal()
        {
            $animals = Animal::getAll();
            $this->assertEquals(2, count($animals));
            $animals[0]->delete();
            $animals = Animal::getAll();
            $this->assertEquals(1, count($animals));
            $animals[0]->delete();
            $animals = Animal::getAll();
            $this->assertEquals(0, count($animals));
        }

        /**
         * @depends testDeleteAnimal
         */
        public function testGetAllWhenThereAreNone()
        {
            $animals = Animal::getAll();
            $this->assertEquals(0, count($animals));
        }

        /**
         * @depends testCreateAndGetAnimalById
         */
        public function testSetBinomialNameAndRetrieveDisplayName()
        {
            $user = User::getByUsername('steven');

            $values = array(
                'Equus zebra',
                'Equus quaggi',
                'Equus gevyi',
            );
            $binomialNameFieldData = CustomFieldData::getByName('BinomialNames');
            $binomialNameFieldData->serializedData = serialize($values);
            $this->assertTrue($binomialNameFieldData->save());

            $animal = new Animal();
            $animal->owner = $user;
            $animal->name = 'Zelda Zebra';
            $animal->binomialName->value = $values[1];
           
            $this->assertTrue($animal->save());
            $this->assertTrue($animal->id !== null);
            $id = $animal->id;
            unset($animal);
            $animal = Animal::getById($id);
            $this->assertEquals('Equus quaggi', $animal->binomialName->value);

            //Confirm a new animal with no defaults set, will not show a default binomialName value.
            $animal = new Animal(false);
            $this->assertNull($animal->binomialName->value);
        }
        /**
         * @depends testSetBinomialNameAndRetrieveDisplayName
         */
        public function testBinomialNameWithDefaultValueDoesntDefaultWhenAnimalSetDefaultsFalse()
        {
            $values = array(
                'Lasiorhinus latifrons',
                'Phascolarctos cinereus',
                'Phascolarctos stirtoni',
                'Vombatus ursinus',
            );
            $binomialNameFieldData = CustomFieldData::getByName('BinomialNames');
            $binomialNameFieldData->serializedData = serialize($values);
            $binomialNameFieldData->defaultValue = $values[3];
            $this->assertTrue($binomialNameFieldData->save());
            $animal = new Animal();
            $this->assertEquals('Vombatus ursinus', $animal->binomialName->value);
            //Set first parameter to false, and confirm the value is null for binomialName
            $animal = new Animal(false);
            $this->assertNull($animal->binomialName->value);
        }

        /**
         * @depends testReplaceOwner
         */
        public function testSetAttributeWithEmptyValue()
        {
            $user = User::getByUsername('benny');
            $this->assertEquals('benny', $user->username);
            $animal = new Animal();
            $animal->name = 'Porky Pig';
            $animal->owner = $user;
            $this->assertTrue($animal->save());
            $animal = Animal::getById($animal->id);
            $this->assertEquals('Porky Pig', $animal->name);
            $fakePostData = array(
                'name' => '',
            );
            $animal->setAttributes($fakePostData);
            $this->assertEquals('', $animal->name);
        }

        public function testOwnerNotPopulatedWhenNoDefaults()
        {
            $animal = new Animal();
            $this->assertEquals('super', $animal->owner->username);
            $animal->validate();
            $animal = new Animal(false);
            $this->assertEquals('', $animal->owner->username);
            $animal->validate();
        }

        /**
         * @depends testCreateAndGetAnimalById
         */
        public function testValidatesWithoutOwnerWhenSpecifyingAttributesToValidate()
        {
            $user = User::getByUsername('steven');
            $this->assertTrue($user->id > 0);
            $animal = new Animal(false);
            $_POST['MassEdit'] = array(
                'maxGestationDays' => '1',
            );
            $_POST['fake'] = array(
                'maxGestationDays' => 4,
            );
            PostUtil::sanitizePostForSavingMassEdit('fake');
            $animal->setAttributes($_POST['fake']);
            $animal->validate(array_keys($_POST['MassEdit']));
            $this->assertEquals(array(), $animal->getErrors());
            $animal->forget();
            $animal = new Animal(false);
            $_POST['MassEdit'] = array(
                'owner' => '1',
            );
            $_POST['fake']  = array(
                'owner'     => array(
                    'id'    => '',
                )
            );
            PostUtil::sanitizePostForSavingMassEdit('fake');
            $animal->setAttributes($_POST['fake']);
            $animal->validate(array_keys($_POST['MassEdit']));
            //there should be an owner error since it is specified but blank
            $this->assertNotEquals(array(), $animal->getErrors());
            $animal->forget();
            $animal = new Animal(false);
            $_POST['MassEdit'] = array(
                'maxGestationDays' => '1',
                'owner'     => '2',
            );
            $_POST['fake'] = array(
                'maxGestationDays' => 4,
                'owner'     => array(
                     'id' => $user->id,
                )
            );
            PostUtil::sanitizePostForSavingMassEdit('fake');
            $animal->setAttributes($_POST['fake']);
            $animal->validate(array_keys($_POST['MassEdit']));
            $this->assertEquals(array(), $animal->getErrors());
        }

        /**
         * @depends testValidatesWithoutOwnerWhenSpecifyingAttributesToValidate
         */
        public function testSettingDefaultValueForType()
        {
            $values = array(
                'Insect',
                'Mammal',
                'Reptile',
               
            );
            $typeFieldData = CustomFieldData::getByName('AnimalTypes');
            $typeFieldData->serializedData = serialize($values);
            $this->assertTrue($typeFieldData->save());

            //Add default value to type attribute for animal.
            $attributeForm = new DropDownAttributeForm();
            $attributeForm->attributeName       = 'type';
            $attributeForm->attributeLabels  = array(
                'de' => 'Type',
                'en' => 'Type',
                'es' => 'Type',
                'fr' => 'Type',
                'it' => 'Type',
            );
            $attributeForm->isAudited           = true;
            $attributeForm->isRequired          = true;
            $attributeForm->defaultValueOrder   = 2;
            $attributeForm->customFieldDataData = $values;
            $attributeForm->customFieldDataName = 'AnimalTypes';

            $modelAttributesAdapterClassName = $attributeForm::getModelAttributeAdapterNameForSavingAttributeFormData();
            $adapter = new $modelAttributesAdapterClassName(new Animal());
            try
            {
                $adapter->setAttributeMetadataFromForm($attributeForm);
            }
            catch (FailedDatabaseSchemaChangeException $e)
            {
                echo $e->getMessage();
                $this->fail();
            }

            $model = new Animal();
            $this->assertEquals($values[2], $model->type->value);

            $user = User::getByUsername('billy');

            $_FAKEPOST = array(
                'name' => 'Barney Bear',
                'type' => array(
                    'value' => $values[1],
                ),
                'owner'     => array(
                     'id' => $user->id,
                )
            );

            $model->setAttributes($_FAKEPOST);
            $this->assertEquals('Mammal', $model->type->value);
            $this->assertTrue($model->save());
        }
        /**
         * @depends testValidatesWithoutOwnerWhenSpecifyingAttributesToValidate
         */
        public function testGetModelClassNames()
        {
            $modelClassNames = AnimalsModule::getModelClassNames();
            $this->assertEquals(1, count($modelClassNames));
            $this->assertEquals('Animal', $modelClassNames[0]);
           
        }

        /**
         * @depends testGetModelClassNames
         */
        public function testCreatingACustomDropDownAfterAnAnimalExists()
        {
            $super = User::getByUsername('super');
            Yii::app()->user->userModel = $super;
            $animal = AnimalTestHelper::createAnimalByNameForOwner('Larry Lion', $super);
            $animalId = $animal->id;
            $animal->forget();

            //Create custom dropdown.
            $values = array(
                'Sirloin',
                'Hamburger',
                'Sausage',
                'Trainer',
            );
            $labels = array('fr' => array('Sirloin fr', 'Hamburger fr', 'Sausage fr', 'Trainer fr'),
                            'de' => array('Sirloin de', 'Hamburger de', 'Sausage de', 'Trainer de'),
            );
            $foodFieldData = CustomFieldData::getByName('Meals');
            $foodFieldData->serializedData = serialize($values);
            $this->assertTrue($foodFieldData->save());

            $attributeForm = new DropDownAttributeForm();
            $attributeForm->attributeName       = 'testMeal';
            $attributeForm->attributeLabels  = array(
                'de' => 'Test Meal 2 de',
                'en' => 'Test Meal 2 en',
                'es' => 'Test Meal 2 es',
                'fr' => 'Test Meal 2 fr',
                'it' => 'Test Meal 2 it',
            );
            $attributeForm->isAudited             = true;
            $attributeForm->isRequired            = true;
            $attributeForm->defaultValueOrder     = 1;
            $attributeForm->customFieldDataData   = $values;
            $attributeForm->customFieldDataName   = 'Meals';
            $attributeForm->customFieldDataLabels = $labels;

            $modelAttributesAdapterClassName = $attributeForm::getModelAttributeAdapterNameForSavingAttributeFormData();
            $adapter = new $modelAttributesAdapterClassName(new Animal());
            $adapter->setAttributeMetadataFromForm($attributeForm);

            $compareData = array(
                'Sirloin',
                'Hamburger',
                'Sausage',
                'Trainer',
            );
            //A new animal will show the values fine.
            $animalNew = new Animal();
            $this->assertEquals($compareData, unserialize($animalNew->testMealCstm->data->serializedData));

            //Now retrieve animal again and make sure you can access the values in the dropdown.
            $animal     = Animal::getById($animalId);
            $this->assertEquals($compareData, unserialize($animal->testMealCstm->data->serializedData));
        }

        /**
         * Should not throw exception, should cast down ok
         * @depends testGetModelClassNames
         */
        public function testCastDown()
        {
            $user                 = User::getByUsername('steven');
            $animal              = new Animal();
            $animal->owner       = $user;
            $animal->name        = 'Willy Wombat';
            $this->assertTrue($animal->save());
            $modelDerivationPathToItem = RuntimeUtil::getModelDerivationPathToItem('Animal');
            $itemId               = $animal->getClassId('Item');
            $item                 = Item::getById($itemId);
            $castedDownModel      = $item->castDown(array($modelDerivationPathToItem));
            $this->assertEquals('Animal', get_class($castedDownModel));
        }
    }
?>
