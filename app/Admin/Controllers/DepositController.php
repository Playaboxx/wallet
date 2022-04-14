<?php

namespace App\Admin\Controllers;

use App\Models\Deposit;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;

class DepositController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Deposits';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Deposit());

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
            // $actions->add(new DepositApprove);
            // $actions->add(new DepositReject);
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
        $grid->column('bank_id', __('Bank id'))->expand(function ($model) {

            $comments = $model->company()->get()->map(function ($comment) {
                return $comment->only(['bank_name', 'holder_name', 'bank_acc']);
            });

            return new Table(['Bank Name', 'Holder Name', 'Bank Account'], $comments->toArray());
        });
        $grid->column('proof', __('Attachment'))->display(function () {
            return asset('deposit/' . $this->proof);
        })->lightbox();
        $grid->column('amount', __('Amount'))->sortable();
        $grid->column('created_at', __('Request Date'))->sortable();
        $grid->column('processed_by_id', __('Processed by id'))->sortable()->expand(function ($model) {

            $user = $model->admin()->get()->map(function ($comment) {
                return $comment->only(['username', 'name']);
            });

            return new Table(['Username', 'Name'], $user->toArray());
        });
        $grid->column('reason_id', __('Reject Reason'))->sortable()->display(function ($id) {
            return \App\Models\Reason::getReason($id);
        });
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
        $show = new Show(Deposit::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('ref_no', __('Ref no'));
        $show->field('user_id', __('User id'));
        $show->field('bank_id', __('Bank id'));
        $show->field('processed_by_id', __('Processed by id'));
        $show->field('proof', __('Proof'));
        $show->field('amount', __('Amount'));
        $show->field('status', __('Status'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('reason_id', __('Reason id'));
        $show->field('others', __('Others'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Deposit());

        $form->text('ref_no', __('Ref no'));
        $form->number('user_id', __('User id'));
        $form->number('bank_id', __('Bank id'));
        $form->number('processed_by_id', __('Processed by id'));
        $form->text('proof', __('Proof'));
        $form->decimal('amount', __('Amount'))->default(0.00);
        $form->number('status', __('Status'));
        $form->number('reason_id', __('Reason id'));
        $form->textarea('others', __('Others'));

        return $form;
    }
}
