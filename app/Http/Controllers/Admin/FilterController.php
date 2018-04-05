<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Category;
use App\Traits\InterfaceTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Filter;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\Parameter;

class FilterController extends Controller
{
    use InterfaceTrait;

    public function index(Request $request, Filter $filter)
    {
        $filters = $filter->orderBy('id', 'desc')->get();
        return view($this->interface . '.filter.index')->withFilters($filters);
    }

    public function category(Request $request, Category $category)
    {
        $category = $category->find($request->id);
        return view($this->interface . '.filter.category')->withCategory($category);
    }

    public function categoryUpdate(Request $request)
    {
        if ($request->order) {
            $order = $request->order;
            unset($order[0]);

            if (!empty($order)) {
                foreach ($order as $key => $value) {
                    $filter = Filter::find($value['item_id']);
                    $filter->weight = $key;
                    $filter->save();
                }
            }
        }
    }

    public function create(Category $category)
    {
        $categories = $category->getCategories();
        return view($this->interface . '.filter.create')->withCategories($categories);
    }

    public function store(Request $request, Filter $filter)
    {
        $validator = $this->storeValidation($request->all());
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $filter->name = $request->name;
        $filter->category_id = $request->category;

        $weight = Filter::where('category_id', $request->category)->max('weight');
        if (!$weight) {
            $weight = 0;
        }
        $weight++;

        $filter->weight = $weight;
        $filter->save();

        if (isset($request->parameters_order)) {
            $parameters_order = json_decode($request->parameters_order);
            unset($parameters_order[0]);
            if ($parameters_order) {
                foreach ($parameters_order as $order) {
                    $parameter = new Parameter();
                    $parameter->name = $request->parameter[$order];
                    $parameter->weight = $order;
                    $filter->parameters()->save($parameter);
                }
            }
        }

        if ($request->cat) {
            return redirect()->route('filter.category', ['id' => $request->cat])->withStatus('Фильтр был успешно создан');
        }
        return redirect()->route('filter.index')->withStatus('Фильтр был успешно создан');
    }

    public function storeValidation($data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'category' => 'required|integer|min:2|exists:categories,id',
            'parameter.*' => 'required_with:parameters_order'
        ]);
    }

    public function show(Request $request, Filter $filter, Category $category)
    {
        $filter = $filter->find($request->id);
        $categories = $category->getCategories();

        return view($this->interface . '.filter.show')->withFilter($filter)->withCategories($categories);
    }

    public function edit(Request $request, Filter $filter)
    {
        $validator = $this->editValidation($request->all());
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $filter = $filter->find($request->filter_id);
        $filter->name = $request->name;
        $filter->category_id = $request->category;

        if ($request->parameters_order) {
            $parameters_order = json_decode($request->parameters_order);
            unset($parameters_order[0]);
            if (!empty($parameters_order)) {
                if ($filter->parameters) {
                    foreach ($parameters_order as $key => $value) {
                        $parameter = $filter->parameters()->where('id', $value)->first();
                        if ($parameter) {
                            $parameter->name = $request->parameter[$value];
                            $parameter->weight = $key;
                            $parameter->save();
                        } else {
                            $parameter = new Parameter();
                            $parameter->name = $request->parameter[$value];
                            $parameter->weight = $key;
                            $filter->parameters()->save($parameter);
                        }
                    }

                    foreach ($filter->parameters as $parameter) {
                        $isset = 0;
                        foreach ($parameters_order as $key => $value) {
                            if ($parameter->id == $value) {
                                $isset = 1;
                            }
                        }
                        if (!$isset) {
                            Parameter::where('id', $parameter->id)->delete();
                        }
                    }
                } else {
                    foreach ($parameters_order as $key => $value) {
                        $parameter = new Parameter();
                        $parameter->name = $request->parameter[$value];
                        $parameter->weight = $key;
                        //$parameter->filter_id = $filter->id;
                        //$parameter->save();
                        $filter->parameters()->save($parameter);
                    }
                }
            } else {
                $filter->parameters()->delete();
            }
        }

        $filter->save();

        if($request->cat){
            return redirect()->route('filter.category', ['id' => $request->cat])->withStatus('Фильтр был успешно изменен');
        }
        return redirect()->route('filter.index')->withStatus('Фильтр был успешно изменен');
    }

    public function editValidation($data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'category' => 'required|integer|min:2|exists:categories,id',
            'parameter.*' => 'required'
        ]);
    }

    public function delete(Request $request, Filter $filter)
    {
        $validator = $this->deleteValidation($request->all());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $filter = $filter->find($request->delete_filter_id);
        if ($filter->parameters) {
            foreach ($filter->parameters as $parameter) {
                $parameter->goods()->detach();
            }
        }
        $filter->parameters()->delete();
        $filter->delete();

        if($request->cat){
            return redirect()->route('filter.category', ['id' => $request->cat])->withStatus('Фильтр был успешно удален');
        }
        return redirect()->route('filter.index')->withStatus('Фильтр был успешно удален');
    }

    public function deleteValidation($data)
    {
        return Validator::make($data, [
            'delete_filter_id' => 'required|integer|exists:filters,id'
        ]);
    }
}
