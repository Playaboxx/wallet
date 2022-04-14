<?php

namespace App\Admin\Controllers;

use App\Models\UserBank;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;

class UserBankController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'User Banks';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UserBank());
        $grid->model()->orderBy('id', 'desc');

        $grid->filter(function ($filter) {
            $filter->equal('user_id', __('User id'));
            $filter->equal('bank_name', __('Bank Name'));
            $filter->equal('holder_name', __('Holder Name'));
            $filter->equal('bank_acc', __('Bank Account Number'));
            $filter->date('created_at', __('Created at'));
            $filter->equal('status', __('Status'))->select([0 => 'Suspend', 1 => 'Active']);
        });

        $grid->disableActions();
        $grid->disableCreateButton();

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('User id'))->expand(function ($model) {

            $comments = $model->user()->get()->map(function ($comment) {
                return $comment->only(['name', 'username', 'nickname', 'email', 'phone', 'birth']);
            });

            return new Table(['Full Name', 'Username', 'Nickname', 'Email', 'Phone No', 'Birth'], $comments->toArray());
        });
        $grid->column('bank_name', __('Bank Name'));
        $grid->column('holder_name', __('Holder Name'));
        $grid->column('bank_acc', __('Bank Account No'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('status', __('Status'))->sortable()->display(function () {
            if ($this->status == 0) {
                return "<span class='text-secondary'>Suspend</span>";
            } else if ($this->status == 1) {
                return "<span class='text-secondary'>Active</span>";
            }
        })->label([
            0 => 'danger',
            1 => 'success'
        ]);

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
        $show = new Show(UserBank::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('bank_name', __('Bank name'));
        $show->field('bank_acc', __('Bank acc'));
        $show->field('holder_name', __('Holder name'));
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
        $form = new Form(new UserBank());

        $form->number('user_id', __('User id'));
        $form->textarea('bank_name', __('Bank name'));
        $form->number('bank_acc', __('Bank acc'));
        $form->textarea('holder_name', __('Holder name'));
        $form->number('status', __('Status'))->default(1);

        return $form;
    }
}
