<?php

namespace App\Admin\Controllers;

use App\Models\Shoes;
use App\Models\ShoesType;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ShoesController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Shoes';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Shoes());
        $grid->model()->latest();
        $grid->name();
        $grid->column('shoes.title', 'Category');
        $grid->sub_title();
        $grid->price();
        $grid->column('description', __('Description'))->display(function($val){
                    return substr($val, 0, 300);
        });
        $grid->column('released','Released')->bool();
        $grid->column('img',__('Thumbnail'))->image('','60','60');
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->like('title',__('Title'));
            $filter->like('shoes.title',__('Category'));
        });


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
        $show = new Show(Shoes::findOrFail($id));



        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Shoes());
        $form->select('type_id',__('Category'))->options((new ShoesType())::selectOptions());
        $form->text('name',__('Name'))->required();
        $form->text('sub_title',__('Sub Title'));
        $form->number('price',__('Price'))->required();
        $form->image('img', __('Thumbnail'))->move('/shoes');
        $form->UEditor('description',__('Content'))->required();
        $states = [
            'on'=> ['value'=>1, 'text'=>'publish'],
            'off'=>['value'=>0, 'text'=>'draft'],
        ];
        $form->switch('released',__('Publish'))->states($states);


        return $form;
    }
}
