<?php

namespace App\Admin\Controllers;

use App\Models\Withdraw;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;

class WithdrawController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Withdrawal';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Withdraw());
        $grid->disableCreateButton();
        $grid->model()->orderBy('id', 'desc');

        $grid->filter(function ($filter) {
            $filter->equal('user_id', __('User id'));
            $filter->equal('bank_id', __('Company Bank id'));
            $filter->equal('processed_by_id', __('Admin id'));
            $filter->date('created_at', __('Request Date'));
            $filter->equal('status', __('Status'))->select([0 => 'Pending', 1 => 'Approve', 2 => 'Reject']);
        });

        $grid->actions(function ($actions) {
            // $actions->add(new WithdrawApprove);
            // $actions->add(new WithdrawReject);
            $actions->disableEdit();
            $actions->disableView();
            $actions->disableDelete();
        });

        $grid->column('id', __('Id'));
        $grid->column('ref_no', __('Ref No.'));
        $grid->column('user_id', __('User id'))->sortable()->expand(function ($model) {

            $comments = $model->user()->get()->map(function ($comment) {
                return $comment->only(['name', 'username', 'nickname', 'email', 'phone', 'birth']);
            });

            return new Table(['Full Name', 'Username', 'Nickname', 'Email', 'Phone No', 'Birth'], $comments->toArray());
        });
        $grid->column('user_bank_id', __('User bank id'))->expand(function ($model) {

            $comments = $model->bank()->get()->map(function ($comment) {
                return $comment->only(['bank_name', 'holder_name', 'bank_acc']);
            });

            return new Table(['Bank Name', 'Holder Name', 'Bank Account'], $comments->toArray());
        });
        $grid->column('amount', __('Amount'));
        $grid->column('created_at', __('Request Date'));
        $grid->column('processed_by_id', __('Processed by id'))->sortable()->expand(function ($model) {

            $user = $model->admin()->get()->map(function ($comment) {
                return $comment->only(['username', 'name']);
            });

            return new Table(['Username', 'Name'], $user->toArray());
        });
        $grid->column('reason_id', __('Reject Reason'))->sortable()->display(function ($id) {
            return \App\Models\Reason::getReason($id);
        });;
        $grid->column('others', __('Others'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('status', __('Status'))->sortable()->display(function () {
            if ($this->status == 0) {
                return "<span class='text-secondary'>Pending</span>";
            } else if ($this->status == 1) {
                return "<span class='text-secondary'>Approve</span>";
            } else if ($this->status == 2) {
                return "<span class='text-secondary'>Reject</span>";
            }
        })->label([
            0 => 'default',
            1 => 'success',
            2 => 'danger'
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
        $show = new Show(Withdraw::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('ref_no', __('Ref no'));
        $show->field('user_id', __('User id'));
        $show->field('user_bank_id', __('User bank id'));
        $show->field('processed_by_id', __('Processed by id'));
        $show->field('amount', __('Amount'));
        $show->field('reason_id', __('Reason id'));
        $show->field('others', __('Others'));
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
        $form = new Form(new Withdraw());

        $form->text('ref_no', __('Ref no'));
        $form->number('user_id', __('User id'));
        $form->number('user_bank_id', __('User bank id'));
        $form->number('processed_by_id', __('Processed by id'));
        $form->decimal('amount', __('Amount'))->default(0.00);
        $form->number('reason_id', __('Reason id'));
        $form->textarea('others', __('Others'));
        $form->number('status', __('Status'));

        return $form;
    }
}
