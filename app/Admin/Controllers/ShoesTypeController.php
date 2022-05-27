<?php

namespace App\Admin\Controllers;

use App\Models\ShoesType;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Tree;
use Encore\Admin\Form;
use Encore\Admin\Layout\Content;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ShoesTypeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'ShoesType';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    /*
    protected function grid()
    {
        $grid = new Grid(new ShoesType());

        $grid->column('title',__('Title'));
        $grid->column('id',__('ID'));


        return $grid;
    }*/
    public function index(Content $content){
        $tree = new Tree(new ShoesType);
        return $content->header('Shoes Category')->body($tree);
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(ShoesType::findOrFail($id));



        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new ShoesType());
        $form->select('parent_id')->options((new ShoesType())::selectOptions());
        $form->text('title')->required();
        $form->number('order')->default(0);


        return $form;
    }
}
