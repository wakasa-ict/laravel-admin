<?php

namespace App\Admin\Controllers;

use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdministratorController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '管理者';
    protected $webRoleId = 3;

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $user = Admin::user();

        $grid = new Grid(new Administrator());

        // Hide systemadmin
        $grid->model()->where('username', '<>', 'admin');

        // ボタン
        $grid->disableExport(); // ダウンロード無効
        $grid->disableRowSelector(); // 行セレクタ無効
        $grid->disableColumnSelector(); // 列セレクタ無効
        $grid->actions(function ($actions) use ($user) {
            $actions->disableView(); // 詳細表示無効
            if (Administrator::find($actions->getKey())->inRoles(['admin', 'manage'])) {
                $actions->disableDelete(); // 削除無効
            }
        });

        $grid->column('id', __('Id'));
        $grid->column('username', __('ユーザーID'));
        $grid->column('name', __('表示名'));

        return $grid;
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Administrator());

        $form->tools(function (Form\Tools $tools){
            $tools->disableView(); // 詳細表示無効
        });
        $form->footer(function ($footer) {
            $footer->disableViewCheck(); // 詳細表示無効
            $footer->disableCreatingCheck(); // 詳細表示無効
            $footer->disableEditingCheck(); // 編集を続ける無効
        });

        $form->text('username', __('ユーザーID'))->creationRules(['required', "unique:admin_users,username"])
        ->updateRules(function($form){
            $id = $form->model()->id;
            return ['required', "unique:admin_users,username,$id"];
        });
        $form->password('password', __('パスワード'))->required();
        $form->password('password_confirmation', 'パスワード(再入力)')->rules('required')
            ->default(function ($form) {
                return $form->model()->password;
            });
        $form->text('name', __('表示名'))->required();

        $form->ignore(['password_confirmation']);

        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = Hash::make($form->password);
            }
        });

        $form->saved(function (Form $form) {
            $administrator = $form->model();

            DB::beginTransaction();
            try{
                if (!$administrator->isRole('administrator')) {
                    $administrator->roles()->sync([$this->webRoleId]);
                }
            }catch(Exception $e){
                DB::rollback();
                \Log::info($e);
                return back()->withInput();
            }
            DB::commit();


        });

        return $form;
    }
}
