<?php

namespace Andruby\DeepAdmin\Controllers;

use Andruby\DeepAdmin\Models\Content;
use Andruby\DeepAdmin\Components\Attrs\SelectOption;

class RemoteSearchController extends AdminController
{
    public function search()
    {
        $pageIndex = request('page', 1);
        $pageSize = request('per_page', 10);
        $query = request('query');

        $extUrlParams = json_decode(request('extUrlParams'), true);
        $formParams = !empty($extUrlParams['val']['form_params']) ? explode("\n", $extUrlParams['val']['form_params']) : [];
        $tableWhereList = !empty($extUrlParams['val']['table_where']) ? explode("\n", $extUrlParams['val']['table_where']) : [];

        $count = 0;
        $options = [];
        if (!empty($formParams) && count($formParams) == 3) {
            $model = new Content($formParams[0]);
            $model = $model->select([$formParams[1], $formParams[2]])->distinct();

            if (!empty($tableWhereList)) {
                foreach ($tableWhereList as $tableWhere) {
                    $tableWhere = !empty($tableWhere) ? explode(",", $tableWhere) : [];
                    $model = $model->where("$tableWhere[0]", "$tableWhere[1]", "$tableWhere[2]")->where(function ($q) use ($formParams, $query) {
                        $q->orWhere($formParams[1], 'like', "%{$query}%");
                        $q->orWhere($formParams[2], 'like', "%{$query}%");
                    });
                }
            } else {
                $model = $model->where($formParams[1], 'like', "%{$query}%")->orWhere($formParams[2], 'like', "%{$query}%");
            }

            $count = $model->count();
            $list = $model->forPage($pageIndex, $pageSize)->get()->toArray();

            if (!empty($list)) {
                foreach ($list as $key => $val) {
                    $options[] = SelectOption::make($val[$formParams[1]], $val[$formParams[2]])->desc($val[$formParams[1]]);
                }
            }
        }

        $data = [
            'data' => [
                'total' => $count,
                'data' => $options,
            ],
        ];

        return $data;
    }
}
