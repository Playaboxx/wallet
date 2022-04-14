<?php

namespace App\Admin\Controllers;

use App\Models\UserRank;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;

class RankController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Ranks';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UserRank());
        $grid->model()->orderBy('id', 'desc');

        $grid->filter(function ($filter) {
            $filter->equal('user_id', __('User id'));
            $filter->where(function ($query) {

                $query->whereHas('vip', function ($query) {
                    $query->where('rank', 'like', "%{$this->input}%");
                });
            }, 'Rank');
            $filter->date('created_at', __('Registered at'));
            $filter->date('updated_at', __('Updated at'));
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
        $grid->column('vip_id', __('Rank'))->sortable()->display(function () {
            if ($this->vip_id == 1) {
                return "<span class='text-secondary'>NORMAL</span>";
            } else if ($this->vip_id == 2) {
                return "<span class='text-secondary'>BRONZE</span>";
            } else if ($this->vip_id == 3) {
                return "<span class='text-secondary'>SILVER</span>";
            } else if ($this->vip_id == 4) {
                return "<span class='text-secondary'>GOLD</span>";
            } else if ($this->vip_id == 5) {
                return "<span class='text-secondary'>PLATINUM</span>";
            }
        })->label([
            1 => 'default',
            2 => 'warning',
            3 => 'success',
            4 => 'info',
            5 => 'danger'
        ]);
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(UserRank::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('vip_id', __('Vip id'));
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
        $form = new Form(new UserRank());

        $form->number('user_id', __('User id'));
        $form->number('vip_id', __('Vip id'));

        return $form;
    }
}
