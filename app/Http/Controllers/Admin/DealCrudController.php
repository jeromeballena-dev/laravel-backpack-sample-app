<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DealRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class DealCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DealCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Deal::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/deal');
        CRUD::setEntityNameStrings('deal', 'deals');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('account_id');
        CRUD::column('deal_name');
        CRUD::column('iso_id');
        CRUD::column('sales_stage')->orderBy(function ($query, $columnDirection) {
            return $query->orderByRaw("FIELD(sales_stage, 'new deal', 'missing info', 'deal won', 'deal lost') $columnDirection");
        });
        CRUD::column('submission_date');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
        // ISO filter
        $this->crud->addFilter([
            'name'  => 'iso_id',
            'type'  => 'dropdown',
            'label' => 'ISO'
        ], function () {
            return \App\Models\Iso::all()->pluck('business_name', 'id')->toArray();
        }, function ($value) {
            $this->crud->addClause('where', 'iso_id', $value);
        });

        // Sales stage filter
        $this->crud->addFilter([
            'name'  => 'sales_stage',
            'type'  => 'dropdown',
            'label' => 'Sales Stage'
        ], [
            'new deal' => 'New Deal',
            'missing info' => 'Missing Info',
            'deal won' => 'Deal Won',
            'deal lost' => 'Deal Lost'
        ], function ($value) {
            $this->crud->addClause('where', 'sales_stage', $value);
        });

        // Submission date filter
        $this->crud->addFilter(
            [
                'type'  => 'date_range',
                'name'  => 'submission_date',
                'label' => 'Submission Date Range'
            ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'submission_date', $value);
            }
        );
        // // Submission date filter
        // $this->crud->addFilter(
        //     [
        //         'type'  => 'date',
        //         'name'  => 'submission_date',
        //         'label' => 'Submission Date'
        //     ],
        //     false,
        //     function ($value) {
        //         $this->crud->addClause('where', 'submission_date', $value);
        //     }
        // );
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(DealRequest::class);

        $this->crud->addField([
            'name' => 'submission_date',
            'type' => 'date_picker',
            'label' => "Submission Date",
        ]);
        CRUD::field('account_id')->label('Account')->type('select2')->model('App\Models\Account')->attribute('business_name')->entity('account');
        CRUD::field('iso_id')->label('ISO')->type('select2')->model('App\Models\Iso')->attribute('business_name')->entity('iso');
        CRUD::field('sales_stage')->type('select_from_array')->options(['new deal' => 'New Deal', 'missing info' => 'Missing Info', 'deal won' => 'Deal Won', 'deal lost' => 'Deal Lost']);
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
