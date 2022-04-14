<?php

namespace App\Admin\Controllers;

use App\Models\Transaction;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;

class TransactionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Transactions';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Transaction());
        $grid->model()->orderBy('id', 'desc');
        $grid->disableCreateButton();
        $grid->disableActions();

        $grid->filter(function ($filter) {
            $filter->equal('user_id', __('User ID'));
            $filter->equal('referral', __('Ref No'));
            $filter->equal('transaction_type', __('Transaction Type'))->select([0 => 'Deposit', 1 => 'Withdraw']);
            $filter->date('created_at', __('Created at'));
        });

        $grid->column('id', __('Id'));
        $grid->column('ref_no', __('Ref No.'));
        $grid->column('user_id', __('User id'))->sortable()->expand(function ($model) {

            $comments = $model->user()->get()->map(function ($comment) {
                return $comment->only(['name', 'username', 'nickname', 'email', 'phone', 'birth']);
            });

            return new Table(['Full Name', 'Username', 'Nickname', 'Email', 'Phone No', 'Birth'], $comments->toArray());
        });
        $grid->column('transaction_type', __('Transaction type'))->sortable()->display(function () {
            if ($this->transaction_type == 0) {
                return "<span class='text-secondary'>Deposit</span>";
            } else if ($this->transaction_type == 1) {
                return "<span class='text-secondary'>Withdraw</span>";
            }
        })->label([
            0 => 'default',
            1 => 'success'
        ]);;
        $grid->column('amount', __('Amount'));
        $grid->column('created_at', __('Created at'));

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
        $show = new Show(Transaction::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('ref_no', __('Ref no'));
        $show->field('user_id', __('User id'));
        $show->field('transaction_type', __('Transaction type'));
        $show->field('amount', __('Amount'));
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
        $form = new Form(new Transaction());

        $form->text('ref_no', __('Ref no'));
        $form->number('user_id', __('User id'));
        $form->number('transaction_type', __('Transaction type'));
        $form->decimal('amount', __('Amount'))->default(0.00);

        return $form;
    }
}
