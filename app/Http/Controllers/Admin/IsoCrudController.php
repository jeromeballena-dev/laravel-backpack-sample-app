<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\IsoRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class IsoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class IsoCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Iso::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/iso');
        CRUD::setEntityNameStrings('ISO', 'ISOs');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('business_name');
        CRUD::column('contact_name');
        $this->crud->addColumn([
            'label' => "Contact number",
            'name' => "contact_number",
            'type' => 'phone',
        ]);
        $this->crud->addColumn([
            'label' => "Emails",
            'name' => "emails",
            'type' => 'multidimensional_array',
            'visible_key' => 'email' // The key to the attribute you would like shown in the enumeration
        ]);

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(IsoRequest::class);

        CRUD::field('business_name');
        CRUD::field('contact_name');
        CRUD::field('contact_number');
        $this->crud->addField([
            'label' => "Emails",
            'name' => "emails",
            'type' => 'repeatable',
            'fields' => [
                [
                    'name'  => 'email',
                    'type'  => 'text',
                    'label' => 'Email',
                ],
            ],
        ]);
        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
