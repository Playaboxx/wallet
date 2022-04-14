<?php

namespace App\Admin\Controllers;

use App\Models\CompanyBank;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CompanyBankController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Company Banks';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CompanyBank());
        $grid->model()->orderBy('id', 'desc');

        $grid->filter(function ($filter) {
            $filter->equal('user_id', __('User id'));
            $filter->equal('bank_name', __('Bank Name'));
            $filter->equal('holder_name', __('Holder Name'));
            $filter->equal('bank_acc', __('Bank Account Number'));
            $filter->date('created_at', __('Created at'));
            $filter->equal('status', __('Status'))->select([0 => 'Suspend', 1 => 'Active']);
        });

        $grid->actions(function ($actions) {
            $actions->disableView();
        });

        $grid->column('id', __('Id'));
        $grid->column('icon', __('Icon'))->lightbox();
        $grid->column('bank_name', __('Bank Name'));
        $grid->column('holder_name', __('Holder Name'));
        $grid->column('bank_acc', __('Bank Account No'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $states = [
            'on'  => ['value' => 1, 'text' => 'Active', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => 'Suspend', 'color' => 'danger'],
        ];
        $grid->column('status')->sortable()->switch($states);

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(CompanyBank::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('bank_name', __('Bank name'));
        $show->field('bank_acc', __('Bank acc'));
        $show->field('holder_name', __('Holder name'));
        $show->field('icon', __('Icon'));
        $show->field('status', __('Status'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new CompanyBank());
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
        });

        $form->image('icon', __('Icon'))->rules(['required', 'image']);
        $form->text('bank_name', __('Bank Name'))->rules(['required']);
        $form->text('holder_name', __('Holder Name'))->rules(['required']);;
        $form->number('bank_acc', __('Bank Account Number'))->rules(['required']);;
        $form->select('status', __('Status'))->options([0 => 'Suspend', 1 => 'Active'])->default(1);

        return $form;
    }
}
