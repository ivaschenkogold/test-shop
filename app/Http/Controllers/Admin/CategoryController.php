<?php

namespace App\Http\Controllers\Admin;

use App\Gold\Slug\NewSlugMaker;
use App\Traits\InterfaceTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use App\Gold\Slug;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use InterfaceTrait;

    public function index(Request $request, Category $category)
    {
        $categories = $category->getCategories();
        return view($this->interface . '.category.index')->withCategories($categories);
    }

    public function create(Category $category)
    {
        $categories = $category->getCategories();
        return view($this->interface . '.category.create')->withCategories($categories);
    }

    public function store(Request $request)
    {
        $validator = $this->storeValidation($request->all());
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $parent = Category::find($request->parent);

        $node = new Category();

        $node->name = $request->name;
        $node->slugMaker = new Slug\NewSlugMaker($node, $request->slug);
        $node->slug = $node->slugMaker->slug;
        $node->title = $request->title;
        $node->desc = $request->desc;
        $node->keywords = $request->keywords;
        $node->text = $request->text;
        $node->appendToNode($parent)->save();

        Category::fixTree();

        return redirect()->route('category.index')->withStatus('Категория была успешно создана');
    }

    public function storeValidation($data)
    {
        return Validator::make($data, [
            'name' => 'required|string|min:1|max:255',
            'title' => 'nullable|string|max:255',
            'desc' => 'nullable|string',
            'keywords' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255',
            'text' => 'nullable|string',
            'parent' => 'integer|exists:categories,id'
        ]);
    }

    public function show(Request $request, Category $category)
    {
        $categories = $category->getCategories();
        $category = Category::find($request->id);
        return view($this->interface . '.category.show')->withCategory($category)->withCategories($categories);
    }

    public function edit(Request $request, Category $category)
    {
        $validator = $this->editValidation($request->all());
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $category = $category->find($request->category_id);

        $category->name = $request->name;
        $category->slugMaker = new Slug\OldSlugMaker($category, $request->slug);
        $category->slug = $category->slugMaker->slug;
        $category->title = $request->title;
        $category->desc = $request->desc;
        $category->keywords = $request->keywords;
        $category->text = $request->text;
        $category->save();

        return redirect()->route('category.index')->withStatus('Катерогия была успешно изменена');
    }

    public function editValidation($data)
    {
        return Validator::make($data, [
            'category_id' => 'required|integer|min:2|exists:categories,id',
            'name' => 'required|string|min:1|max:255',
            'title' => 'nullable|string|max:255',
            'desc' => 'nullable|string',
            'keywords' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $data['category_id'] . ',id',
            'text' => 'nullable|string'
        ]);
    }

    public function rebuildTree(Request $request)
    {
        $tree = $request->tree;
        $update_arr = [];

        foreach ($tree as $key => $category) {
            if ($tree[$key]['item_id'] == 'root') {
                $id = 1;
            } else {
                $id = $tree[$key]['item_id'];
            }

            if ($tree[$key]['parent_id'] == 'none') {
                $parent = null;
            } elseif ($tree[$key]['parent_id'] == 'root') {
                $parent = 1;
            } else {
                $parent = $tree[$key]['parent_id'];
            }

            $update_arr[$id] = [];
            $update_arr[$id]['parent_id'] = $parent;
            $update_arr[$id]['_lft'] = $tree[$key]['left'];
            $update_arr[$id]['_rgt'] = $tree[$key]['right'];
        }

        foreach ($update_arr as $key => $value) {
            Category::where('id', $key)->update($value);
        }

        return response()->json(['success' => 1]);
    }

    public function delete(Request $request, Category $category)
    {
        $validator = $this->deleteValidation($request->all());
        $count = $category->where('id', $request->delete_category_id)->first()->goods->count();
        $validator->after(function ($validator) use ($request, $count) {
            if ($count > 0) {
                $move_to = Category::where('id', $request->move_goods_to)->first();
                if (!$move_to) {
                    $validator->errors()->add('move_goods_to', 'Нету такой категории');
                }elseif($move_to->id == 1){
                    $validator->errors()->add('move_goods_to', 'Вы не можкте переместить товары в эту категорию');
                }elseif($move_to->id == $request->delete_category_id){
                    $validator->errors()->add('move_goods_to', 'Вы не можете переместить товары в удаляемую категорию');
                }
            }
        });
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $category = $category->find($request->delete_category_id);
        if ($count > 0) {
            $category->goods()->update(['category_id' => $request->move_goods_to]);
        }
        $category->delete();

        Category::fixTree();

        return redirect()->route('category.index')->withStatus('Категоия была успешно удалена');
    }

    public function checkDelete(Request $request, Category $category)
    {
        $validator = $this->deleteValidation($request->all());
        if ($validator->fails()) {
            return response()->json(['success' => 0]);
        }

        $count = $category->where('id', $request->delete_category_id)->first()->goods->count();
        return response()->json(['success' => 1, 'count' => $count]);
    }

    public function deleteValidation($data)
    {
        return Validator::make($data, [
          'delete_category_id' => 'required|integer|min:2|exists:categories,id'
        ]);
    }
}
