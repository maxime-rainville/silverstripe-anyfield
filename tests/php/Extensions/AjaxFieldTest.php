<?php

namespace SilverStripe\AnyField\Tests\Extensions;

use SilverStripe\Admin\LeftAndMain;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\AnyField\Extensions\AjaxField;
use SilverStripe\CMS\Forms\AnchorSelectorField;
use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\Session;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FieldList;
use SilverStripe\LinkField\Models\SiteTreeLink;

class AjaxFieldTest extends SapphireTest
{

    protected $use_database = false;

    public function testExtensionApplied()
    {
        $field = TreeDropdownField::create('Name', 'Label', SiteTree::class);
        $this->assertTrue($field->hasExtension(AjaxField::class), 'AjaxField is applied to TreeDropdownField');

        $field = AnchorSelectorField::create('Name');
        $this->assertTrue($field->hasExtension(AjaxField::class), 'AjaxField is applied to TreeDropdownField');
    }

    public function testUpdateLinkModalForm()
    {
        $request = new HTTPRequest(
            'GET',
            'admin/methodSchema/Modals/AnyFieldForm',
            ['key' => SiteTreeLink::class],
            []
        );
        $request->setSession(new Session([]));
        $leftAndMain = LeftAndMain::create();
        $controller = $leftAndMain->Modals();
        $leftAndMain->setRequest($request);

        $field = TreeDropdownField::create('Name', 'Label', SiteTree::class);

        Form::create(
            $controller,
            'Modals/AnyFieldForm',
            FieldList::create($field)
        );

        $this->assertEquals(
            'admin/Modals/Modals/AnyFieldForm/field/Name?key=' . urlencode(SiteTreeLink::class),
            $field->Link(),
            'Link is updated with the key'
        );
    }

    public function testDontUpdateLinkModalForm()
    {
        $request = new HTTPRequest(
            'GET',
            'admin',
            ['key' => SiteTreeLink::class],
            []
        );
        $request->setSession(new Session([]));
        $leftAndMain = LeftAndMain::create();
        $leftAndMain->setRequest($request);

        $field = TreeDropdownField::create('Name', 'Label', SiteTree::class);

        Form::create(
            $leftAndMain,
            'LeftAndMain',
            FieldList::create($field)
        );

        $this->assertEquals(
            'admin/LeftAndMain/field/Name',
            $field->Link(),
            'Link is updated with the key'
        );
    }
}
