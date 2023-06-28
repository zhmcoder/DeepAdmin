<?php


namespace Andruby\DeepAdmin\Grid;


use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Andruby\DeepAdmin\Grid\Filter\AbstractFilter;
use Andruby\DeepAdmin\Grid\Filter\Scope;

/**
 * Class Filter
 * @method AbstractFilter equal($column, $label = '')
 * @method AbstractFilter notEqual($column, $label = '')
 * @method AbstractFilter like($column, $label = '')
 * @method AbstractFilter contains($column, $label = '')
 * @method AbstractFilter startsWith($column, $label = '')
 * @method AbstractFilter endsWith($column, $label = '')
 * @method AbstractFilter ilike($column, $label = '')
 * @method AbstractFilter gt($column, $label = '')
 * @method AbstractFilter lt($column, $label = '')
 * @method AbstractFilter between($column, $label = '')
 * @method AbstractFilter in($column, $label = '')
 * @method AbstractFilter notIn($column, $label = '')
 * @method AbstractFilter where($callback, $label = '', $column = null)
 * @method AbstractFilter date($column, $label = '')
 * @method AbstractFilter day($column, $label = '')
 * @method AbstractFilter month($column, $label = '')
 * @method AbstractFilter year($column, $label = '')
 */
class Filter
{
    /**
     * @var Model
     */
    protected $model;

    protected $filters = [];
    protected $filterFormData = [];
    protected $leftAction = '';
    protected $reload = 'grid';

    protected $name = '';

    protected $expand = false;
    protected $exportUri = null;
    protected $exportUriText = null;
    protected $exportPdf = null;
    protected $exportPdfText = null;
    protected $reloadGrid = true;

    /**
     * @var Collection
     */
    protected $scopes;

    protected static $supports = [
        'equal' => Filter\Equal::class,
        'notEqual' => Filter\NotEqual::class,
        'ilike' => Filter\Ilike::class,
        'like' => Filter\Like::class,
        'gt' => Filter\Gt::class,
        'lt' => Filter\Lt::class,
        'between' => Filter\Between::class,
        'where' => Filter\Where::class,
        'in' => Filter\In::class,
        'notIn' => Filter\NotIn::class,
        'date' => Filter\Date::class,
        'day' => Filter\Day::class,
        'month' => Filter\Month::class,
        'year' => Filter\Year::class,
        'contains' => Filter\Like::class,
        'startsWith' => Filter\StartsWith::class,
        'endsWith' => Filter\EndsWith::class,
    ];

    public function __construct($model)
    {
        $this->model = $model;

        $this->scopes = new Collection();
    }

    public function conditions()
    {
        $inputs = Arr::dot(request()->all());


        $inputs = array_filter($inputs, function ($input) {
            return $input !== '' && !is_null($input);
        });


        $this->sanitizeInputs($inputs);

        if (empty($inputs)) {
            return [];
        }

        $params = [];

        foreach ($inputs as $key => $value) {
            Arr::set($params, $key, $value);
        }

        $conditions = [];


        foreach ($this->filters() as $filter) {
            $conditions[] = $filter->condition($params);
        }


        return tap(array_filter($conditions), function ($conditions) {
            if (!empty($conditions)) {
                $this->expand();
            }
        });
    }

    protected function sanitizeInputs(&$inputs)
    {
        if (!$this->name) {
            return $inputs;
        }

        $inputs = collect($inputs)->filter(function ($input, $key) {
            return Str::startsWith($key, "{$this->name}_");
        })->mapWithKeys(function ($val, $key) {
            $key = str_replace("{$this->name}_", '', $key);

            return [$key => $val];
        })->toArray();
    }


    public function expand()
    {
        $this->expand = true;

        return $this;
    }

    /**
     * 搜索导出按钮，空不展示
     * @param $exportUri
     * @return $this
     */
    public function exportUri($exportUri)
    {
        $this->exportUri = $exportUri;

        return $this;
    }

    public function exportUriText($exportUriText)
    {
        $this->exportUriText = $exportUriText;

        return $this;
    }

    public function exportPdf($exportPdf)
    {
        $this->exportPdf = $exportPdf;

        return $this;
    }

    public function exportPdfText($exportPdfText)
    {
        $this->exportPdfText = $exportPdfText;

        return $this;
    }

    public function reloadGrid($reloadGrid = true)
    {
        $this->reloadGrid = $reloadGrid;

        return $this;
    }

    public function leftAction($leftAction = '')
    {
        $this->leftAction = $leftAction;
        return $this;
    }

    public function reload($reload = 'grid')
    {
        $this->reload = $reload;
        return $this;
    }

    public function execute($toArray = true)
    {
        if (method_exists($this->model->eloquent(), 'paginate')) {
            $this->model->usePaginate(true);
            return $this->model->buildData($toArray);
        }

        $conditions = array_merge(
            $this->conditions(),
            $this->scopeConditions()
        );


        return $this->model->addConditions($conditions)->buildData($toArray);
    }

    public function getCurrentScope()
    {
        $key = request(Scope::QUERY_NAME);

        return $this->scopes->first(function ($scope) use ($key) {
            return $scope->key == $key;
        });
    }

    protected function scopeConditions()
    {
        if ($scope = $this->getCurrentScope()) {
            return $scope->condition();
        }

        return [];
    }

    /**
     * @return array
     */
    public function filters(): array
    {
        return $this->filters;
    }


    /**
     * @return array
     */
    public function buildFilter(): array
    {

        foreach ($this->filters as $filter) {
            Arr::set($this->filterFormData, $filter->getColumn(), $filter->getDefaultValue());
        }

        return [
            'filters' => $this->filters,
            'filterFormData' => $this->filterFormData,
            'leftAction' => $this->leftAction,
            'reload' => $this->reload,
            'exportUri' => $this->exportUri,
            'exportUriText' => $this->exportUriText,
            'exportPdf' => $this->exportPdf,
            'exportPdfText' => $this->exportPdfText,
            'reloadGrid' => $this->reloadGrid
        ];
    }

    protected function addFilter(AbstractFilter $filter)
    {
        return $this->filters[] = $filter;
    }

    public function resolveFilter($abstract, $arguments)
    {
        if (isset(static::$supports[$abstract])) {
            return new static::$supports[$abstract](...$arguments);
        }
    }

    public function __call($method, $arguments)
    {
        if ($filter = $this->resolveFilter($method, $arguments)) {
            return $this->addFilter($filter);
        }

        return $this;
    }

}
