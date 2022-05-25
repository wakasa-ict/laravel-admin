<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\Auth\Database\OperationLog;
use Encore\Admin\Grid;
use Illuminate\Support\Arr;

class LogController extends AdminController
{
    /**
     * {@inheritdoc}
     */
    protected function title()
    {
        return trans('admin.operation_log');
    }

    /**
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OperationLog());

        $grid->model()->orderBy('id', 'DESC');

        $grid->export(function ($export) {
            $log = new OperationLog();
            $originalColumns = collect($log->getConnection()->getSchemaBuilder()->getColumnListing($log->getTable()));
            $export->originalValue($originalColumns->toArray());
        });

        $grid->column('id', 'ID')->sortable();
        $grid->column('user.name', '管理者');
        $grid->column('method', 'メソッド')->display(function ($method) {
            $color = Arr::get(OperationLog::$methodColors, $method, 'grey');

            return "<span class=\"badge bg-$color\">$method</span>";
        });
        $grid->column('path')->label('info');
        $grid->column('ip', 'IP')->label('primary');
        $grid->column('input')->display(function ($input) {
            $input = json_decode($input, true);
            $input = Arr::except($input, ['_pjax', '_token', '_method', '_previous_']);
            if (empty($input)) {
                return '<code>{}</code>';
            }

            return '<pre>'.json_encode($input, JSON_PRETTY_PRINT | JSON_HEX_TAG).'</pre>';
        });

        $grid->column('created_at', '日時');

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableEdit();
            $actions->disableView();
            $actions->disableDelete();
        });

        $grid->disableCreateButton();

        $grid->filter(function (Grid\Filter $filter) {
            $userModel = config('admin.database.users_model');

            $filter->equal('user_id', '管理者')->select($userModel::all()->pluck('name', 'id'));
            $filter->equal('method', 'メソッド')->select(array_combine(OperationLog::$methods, OperationLog::$methods));
            $filter->like('path');
            $filter->equal('ip', 'IP');
        });

        return $grid;
    }

    /**
     * @param mixed $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $ids = explode(',', $id);

        if (OperationLog::destroy(array_filter($ids))) {
            $data = [
                'status'  => true,
                'message' => trans('admin.delete_succeeded'),
            ];
        } else {
            $data = [
                'status'  => false,
                'message' => trans('admin.delete_failed'),
            ];
        }

        return response()->json($data);
    }
}
