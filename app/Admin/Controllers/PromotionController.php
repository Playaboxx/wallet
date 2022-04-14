<?php

namespace App\Admin\Controllers;

use App\Models\Promotion;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PromotionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Promotions';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Promotion());
        $grid->model()->orderBy('id', 'desc');
        $grid->filter(function ($filter) {

            $filter->disableIdFilter();
            $filter->equal('status', __('Status'))->select([0 => 'Suspend', 1 => 'Active']);
        });

        $grid->actions(function ($actions) {
            $actions->disableView();
        });

        $grid->column('id', __('Id'));
        $grid->column('type', __('Type'))->sortable()->display(function () {
            if ($this->type == 0) {
                return "<span class='text-secondary'>All</span>";
            } else if ($this->type == 1) {
                return "<span class='text-secondary'>Esport</span>";
            }
        })->label([
            0 => 'danger',
            1 => 'success'
        ]);
        $grid->column('title', __('Title'));
        $grid->column('content', __('Content'));
        $grid->column('image', __('Image'))->lightbox();
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
        $show = new Show(Promotion::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('content', __('Content'));
        $show->field('image', __('Image'));
        $show->field('type', __('Type'));
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
        $form = new Form(new Promotion());
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
        });

        $form->select('type', __('Type'))->options([0 => 'All', 1 => 'Esport'])->default(0);
        $form->text('title', __('Title'))->rules('required|string');
        $form->textarea('content', __('Content'))->rules('required|string');
        $form->image('image', __('Image'))->rules(['required', 'image']);
        $form->select('status', __('Status'))->options([0 => 'Suspend', 1 => 'Active'])->default(1);

        return $form;
    }
}
