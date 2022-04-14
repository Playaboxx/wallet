<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Models\UserRank;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Hash;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Users';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());
        $grid->model()->orderBy('id', 'desc');

        $grid->filter(function ($filter) {
            $filter->equal('name', __('Full Name'));
            $filter->equal('name', __('Username'));
            $filter->equal('name', __('Nickname'));
            $filter->equal('email', __('Email'));
            $filter->equal('phone', __('Phone Number'));
            $filter->date('created_at', __('Registered at'));
            $filter->equal('status', __('Status'))->select([0 => 'Suspend', 1 => 'Active']);
        });

        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableDelete();
        });

        $grid->column('id', __('Id'));
        $grid->column('name', __('Full Name'));
        $grid->column('username', __('Userame'));
        $grid->column('nickname', __('Nickname'));
        $grid->column('email', __('Email'));
        $grid->column('phone', __('Phone No'));
        $grid->column('balance', __('Balance'));
        $grid->column('birth', __('Date of Birth'));
        $grid->column('created_at', __('Registered at'));
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
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        $show->field('email_verified_at', __('Email verified at'));
        $show->field('password', __('Password'));
        $show->field('remember_token', __('Remember token'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('username', __('Username'));
        $show->field('nickname', __('Nickname'));
        $show->field('phone', __('Phone'));
        $show->field('balance', __('Balance'));
        $show->field('birth', __('Birth'));
        $show->field('status', __('Status'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User());
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
            $tools->disableDelete();
        });
        $form->text('name', __('Full Name。'))
            ->creationRules(['required', "unique:users"])
            ->updateRules(['required', "unique:users,name,{{id}}"]);
        $form->text('username', __('Username'))
            ->creationRules(['required', "unique:users"])
            ->updateRules(['required', "unique:users,username,{{id}}"]);
        $form->text('nickname', __('Nickname'))
            ->creationRules(['required', "unique:users"])
            ->updateRules(['required', "unique:users,nickname,{{id}}"]);
        $form->password('password', __('Password'))->rules('required|min:8|string');
        $form->email('email', __('Email'))
            ->creationRules(['required', "unique:users", 'email'])
            ->updateRules(['required', "unique:users,email,{{id}}", 'email']);
        $form->text('phone', __('Phone Number'))
            ->creationRules(['required', "unique:users", 'regex:/^\d+$/', 'min:10'])
            ->updateRules(['required', "unique:users,phone,{{id}}", 'regex:/^\d+$/', 'min:10']);
        $form->decimal('balance', __('Balance'))->default(0.00);
        $form->date('birth', __('Date of Birth'))->default(date('Y-m-d'));
        $form->select('status', __('Status'))->options([0 => 'Suspend', 1 => 'Active'])->default(1);

        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = Hash::make($form->password);
            } else {
                $form->password = $form->model()->password;
            }
            if ($form->referred_by == $form->model()->id) {
                $form->referred_by = null;
            }
        });

        $form->saved(function (Form $form) {
            $user = UserRank::where('user_id', $form->model()->id)->first();

            if ($user == null) {
                UserRank::create([
                    'user_id' => $form->model()->id,
                    'vip_id' => 1
                ]);
            }
        });

        return $form;
    }
}
