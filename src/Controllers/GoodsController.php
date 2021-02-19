<?php

namespace Andruby\DeepAdmin\Controllers;

use Andruby\DeepAdmin\Components\GoodsSku;
use Andruby\DeepAdmin\Models\Goods;
use Andruby\DeepAdmin\Models\Brand;
use Andruby\DeepAdmin\Models\GoodsAttr;
use Andruby\DeepAdmin\Models\GoodsAttrMap;
use Andruby\DeepAdmin\Models\GoodsAttrValue;
use Andruby\DeepAdmin\Models\GoodsAttrValueMap;
use Andruby\DeepAdmin\Models\GoodsClass;
use Illuminate\Http\Request;
use SmallRuralDog\Admin\Components\Attrs\SelectOption;
use SmallRuralDog\Admin\Components\Form\Cascader;
use SmallRuralDog\Admin\Components\Form\CSwitch;
use SmallRuralDog\Admin\Components\Form\DatePicker;
use SmallRuralDog\Admin\Components\Form\Input;
use SmallRuralDog\Admin\Components\Form\Radio;
use SmallRuralDog\Admin\Components\Form\RadioGroup;
use SmallRuralDog\Admin\Components\Form\Select;
use SmallRuralDog\Admin\Components\Form\Upload;
use SmallRuralDog\Admin\Components\Form\WangEditor;
use SmallRuralDog\Admin\Components\Grid\Image;
use SmallRuralDog\Admin\Components\Grid\Tag;
use SmallRuralDog\Admin\Components\Widgets\Divider;
use SmallRuralDog\Admin\Controllers\AdminController;
use SmallRuralDog\Admin\Form;
use SmallRuralDog\Admin\Grid;

class GoodsController extends AdminController
{

    public function grid()
    {
        $grid = new Grid(new Goods());

        $grid->quickSearch(['name']);

        $grid->column('id', "ID")->width(100)->sortable();
        $grid->column('cover.path', '产品图片')->width(70)->component(Image::make()->size(50, 50)->preview())->align("center");
        $grid->column('name', " ")->width(400);
        $grid->column('goodsClass.name', "产品分类")->width(100);
        $grid->column('brand.name', "品牌")->width(100);
        $grid->column('putaway', "是否上架")->width(100)->align("center")->customValue(function ($row, $value) {
            return $value == 1 ? "上架" : "下架";
        })->component(Tag::make()->type(["上架" => "success", "下架" => "danger"]));

        $grid->column('created_at', '发布时间');


        $grid->actions(function (Grid\Actions $actions) {
        });

        $grid->filter(function (Grid\Filter $filter) {
            $filter->equal('brand_id', '所属品牌')->component(Select::make()->options(function () {
                return Brand::query()->get()->map(function ($item) {
                    return SelectOption::make($item->id, $item->name);
                })->all();
            }));
            $filter->date('created_at', '发布日期')->component(DatePicker::make());
            //$filter->between('created_at', '日期范围')->component(DatePicker::make()->type("daterange"));
            $filter->equal('putaway', '上下架')->component(RadioGroup::make(null, [
                Radio::make(1, '上架'),
                Radio::make(0, '下架'),
            ]));
        });

        $grid->toolbars(function (Grid\Toolbars $toolbars) {
            $toolbars->createButton()->content("添加产品");
            $toolbars->addRight(Grid\Tools\ToolButton::make("页面源代码")->handler("link")->uri("https://github.com/SmallRuralDog/laravel-vue-admin-demo/blob/master/app/Admin/Controllers/GoodsController.php"));
            $toolbars->addRight(Grid\Tools\ToolButton::make("SKU字段扩展源代码")->handler("link")->uri("https://github.com/SmallRuralDog/laravel-vue-admin-demo/tree/master/app/Admin/Extends/LaravelVueAdminDemoExtend"));
        });

        return $grid;
    }

    public function form($isEdit = false)
    {
        $form = new Form(new Goods());
        $form->labelWidth("250px");

        $form->item('name', "商品名称")->required()->topComponent(Divider::make("基本信息"));
        $form->item('brand_id', "商品品牌")->required()->serveRules("min:1")->component(Select::make(null)->filterable()->options(function () {
            return Brand::query()->orderBy('index_name')->get()->map(function ($item) {
                return SelectOption::make($item->id, $item->name)->avatar(admin_file_url($item->icon))->desc(strtoupper($item->index_name));
            })->all();
        }));
        $form->item("goods_class_path", "产品分类")->required()->component(function () {
            $goods_class = new GoodsClass();
            $allNodes = $goods_class->toTree();
            return Cascader::make()->options($allNodes)->value("id")->label("name")->expandTrigger("hover");
        });
        $form->item("images", "商品图片")->required()
            ->component(Upload::make()->width(130)
                ->height(130)->multiple(true, "id", "path")->limit(10))
            ->help("尺寸750x750像素以上，大小2M以下,最多10张图片，第一张为产品主图");
        $form->item('description', "商品卖点")->help("选填，商品卖点简述，例如：此款商品美观大方 性价比较高 不容错过");

        $form->item('one_attr', "规格类型")->component(RadioGroup::make(0)->options([
            Radio::make(1, "单规格"),
            Radio::make(0, "多规格"),
        ])->disabled($isEdit))->topComponent(Divider::make("规格/库存"))->help("保存后无法修改");

        $form->item("price", "价格")->vif("one_attr", 1)->component(Input::make(0)->append("元"))->inputWidth(5);
        $form->item("cost_price", "进货价")->vif("one_attr", 1)->component(Input::make(0)->append("元"))->inputWidth(5);
        $form->item("line_price", "划线价")->vif("one_attr", 1)->component(Input::make(0)->append("元"))->inputWidth(5);
        $form->item("stock_num", "库存")->vif("one_attr", 1)->component(Input::make(0)->append("元"))->inputWidth(5);
        $form->item("goods_sku", "产品规格")->vif("one_attr", 0)->component(GoodsSku::make())->help("这是自定义组件，<a target='_blank' href='https://github.com/SmallRuralDog/laravel-vue-admin-demo/blob/master/app/Admin/Extends/LaravelVueAdminDemoExtend/resources/js/components/GoodsSku.vue'>查看源代码</a>");


        $form->item("putaway", "上架")->component(CSwitch::make());

        $form->item("content.content", "产品详情")->component(WangEditor::make())->topComponent(Divider::make("详细信息"));


        $form->addValidatorRule([
            'goods_sku.goods_sku_list.*.price' => ["numeric", "min:0.01"]
        ], [
            'goods_sku.goods_sku_list.*.price.min' => '价格最小为0.01'
        ]);


        $form->saving(function (Form $form) {
            $form->goods_class_id = collect($form->input("goods_class_path"))->last();
            $form->user_id = \Admin::user()->id;
            $form->store_id = 0;
            $form->images = collect($form->input("images"))->map(function ($item, $index) {
                $item["order"] = $index;
                return $item;
            })->all();
        });

        $form->editQuery(function (Form $form, $editData) {
            $form->editData["goods_sku"] = [
                "goods_attrs" => $form->model()->attr_map,
                "goods_sku_list" => $form->model()->skus,
            ];
        });


        $form->DbTransaction(function (form $form) {
            /**@var Goods $goods */
            $goods = $form->model();
            try {
                $attrs = $form->input("goods_sku")["goods_attrs"] ?? [];
                GoodsAttrMap::query()->where('goods_id', $goods->id)->delete();
                GoodsAttrValueMap::query()->where('goods_id', $goods->id)->delete();
                collect($attrs)->map(function ($attr, $index) use ($goods) {
                    $attr_map = GoodsAttrMap::query()->create([
                        'goods_id' => $goods->id,
                        'attr_id' => $attr['id'],
                        'index' => $index
                    ]);
                    $values = collect($attr['sku_list'])->filter(function ($item) {
                        return @$item['id'] > 0;
                    })->map(function ($value, $index) use ($attr_map) {
                        return [
                            'attr_map_id' => $attr_map->id,
                            'goods_id' => $attr_map->goods_id,
                            'attr_id' => $attr_map->attr_id,
                            'attr_value_id' => $value['id'],
                            'image' => @$value['image'],
                            'index' => $index
                        ];
                    })->all();
                    GoodsAttrValueMap::query()->insert($values);
                });
            } catch (\Exception $exception) {
                abort(400, '销售属性保存失败' . config('app.debug') ? $exception->getMessage() : '');
            }
            try {
                $skus = $form->input("goods_sku")['goods_sku_list'] ?? [];
                //首先将原有的删除
                \Andruby\DeepAdmin\Models\GoodsSku::setSkuStatus($goods, -1);


                if (collect($skus)->count() <= 0) {
                    //无商品规格
                    //更新或创建
                    $goods_sku = \Andruby\DeepAdmin\Models\GoodsSku::query()
                        ->where('goods_id', $goods->id)
                        ->where('attr_key', "0")
                        ->updateOrCreate([], [
                            'goods_id' => $goods->id,
                            'name' => $goods->name,
                            'attr_key' => "0",
                            'image' => $goods->cover->path,
                            'price' => $form->price,
                            'cost_price' => $form->stock_price ?? 0.00,
                            'line_price' => $form->line_price ?? 0.00,
                            'code' => $form->code ?? "",
                            'sold_num' => $form->sold_num ?? 0,
                            'status' => 1
                        ]);
                    //更新库存
                    \Andruby\DeepAdmin\Models\GoodsSku::setSkuStock($goods, $goods_sku, $form->stock_num);

                } else {
                    collect($skus)->filter(function ($item) {
                        return is_array($item);
                    })->map(function ($sku) use ($goods) {
                        //更新或创建
                        $goods_sku = \Andruby\DeepAdmin\Models\GoodsSku::query()
                            ->where('goods_id', $goods->id)
                            ->where('attr_key', $sku['attr_key'])
                            ->updateOrCreate([], [
                                'goods_id' => $goods->id,
                                'name' => '',
                                'attr_key' => $sku['attr_key'],

                                'image' => $sku['image'] ?? $goods->cover->path,
                                'price' => $sku['price'],
                                'cost_price' => $sku['cost_price'] ?? 0.00,
                                'line_price' => $sku['line_price'] ?? 0.00,
                                'code' => $sku['code'],
                                'sold_num' => $sku['sold_num'] ?? 0,
                                'status' => 1
                            ]);
                        //更新库存
                        \Andruby\DeepAdmin\Models\GoodsSku::setSkuStock($goods, $goods_sku, $sku['stock_num']);

                        \Andruby\DeepAdmin\Models\GoodsSku::setSkuAttrValueMap($goods, $goods_sku, $sku['attrs']);
                        //TODO 根据订单关联，更新销量
                    });
                }
            } catch (\Exception $exception) {
                abort(400, 'SKU保存失败' . config('app.debug') ? $exception->getMessage() : '');
            }
        });
        return $form;
    }


    public function addGoodsAttr(Request $request)
    {
        try {
            \Admin::validatorData($request->all(), [
                'name' => 'required|unique:goods_attrs,name'
            ], [
                'name.required' => '请输入名称',
                'name.unique' => '名称已存在',
            ]);
            $name = $request->input("name");

            $ga = GoodsAttr::query()->create([
                'store_id' => 0,
                'name' => $name,
                'sort' => 1
            ]);
            return \Admin::response($ga->allAttrs());

        } catch (\Exception $exception) {
            return \Admin::responseError($exception->getMessage());
        }
    }

    public function addGoodsAttrValue(Request $request)
    {
        try {
            \Admin::validatorData($request->all(), [
                'name' => 'required|unique:goods_attr_values,name',
                'goods_attr_id' => 'required|numeric|min:1'
            ], [
                'name.required' => '请输入名称',
                'name.unique' => '名称已存在',
            ]);
            $name = $request->input("name");
            $goods_attr_id = $request->input("goods_attr_id");

            $ga = GoodsAttrValue::query()->create([
                'goods_attr_id' => $goods_attr_id,
                'store_id' => 0,
                'name' => $name,
                'sort' => 1
            ]);
            return \Admin::response($ga->allValues($goods_attr_id));

        } catch (\Exception $exception) {
            return \Admin::responseError($exception->getMessage());
        }
    }

}
