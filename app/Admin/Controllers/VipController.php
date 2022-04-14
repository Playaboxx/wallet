<?php

namespace App\Admin\Controllers;

use App\Models\Vip;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class VipController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Vip';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Vip());
        $grid->model()->orderBy('id', 'desc');

        $grid->actions(function ($actions) {
            $actions->disableView();
        });

        $grid->column('id', __('Id'));
        $grid->column('rank', __('Rank'));
        $grid->column('livecasinorebates', __('Live Casino Rebates'));
        $grid->column('sportsbookrebates', __('Sportsbook Rebates'));
        $grid->column('slotsrebate', __('Slots Rebate'));
        $grid->column('birthdaybonus', __('Birthdayb Bonus'));
        $grid->column('upgradebonus', __('Upgrade Bonus'));
        $grid->column('withdrawalfrequency', __('Withdrawal Frequency'));
        $grid->column('withdrawalamount', __('Withdrawal Amount'));
        $grid->column('withdrawalchannels', __('Withdrawal Channels'));
        $grid->column('amount', __('>Balance'));
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
        $show = new Show(Vip::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('rank', __('Rank'));
        $show->field('livecasinorebates', __('Livecasinorebates'));
        $show->field('sportsbookrebates', __('Sportsbookrebates'));
        $show->field('slotsrebate', __('Slotsrebate'));
        $show->field('birthdaybonus', __('Birthdaybonus'));
        $show->field('upgradebonus', __('Upgradebonus'));
        $show->field('withdrawalfrequency', __('Withdrawalfrequency'));
        $show->field('withdrawalamount', __('Withdrawalamount'));
        $show->field('withdrawalchannels', __('Withdrawalchannels'));
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
        $form = new Form(new Vip());
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
        });

        $form->text('rank', __('Rank'))->rules('required|string');
        $form->decimal('livecasinorebates', __('Live Casino Rebates'))->default(0.0000);
        $form->decimal('sportsbookrebates', __('Sportsbook Rebates'))->default(0.0000);
        $form->decimal('slotsrebate', __('Slots Rebate'))->default(0.0000);
        $form->number('birthdaybonus', __('Birthday Bonus'))->rules('required|string');
        $form->number('upgradebonus', __('Upgrade Bonus'))->rules('required|string');
        $form->number('withdrawalfrequency', __('Withdrawal Frequency'))->default(1);
        $form->decimal('withdrawalamount', __('Withdrawal Amount'))->default(0.00);
        $form->text('withdrawalchannels', __('Withdrawal Channels'))->rules('required|string');
        $form->decimal('amount', __('>Balance'))->default(0.00);

        return $form;
    }
}
