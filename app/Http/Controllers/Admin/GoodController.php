<?php

namespace App\Http\Controllers\Admin;

use App\Traits\InterfaceTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Good\Good;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\Category;
use App\Gold\Slug;
use App\Models\Admin\Good\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class GoodController extends Controller
{
    use InterfaceTrait;

    public function index(Good $good)
    {
        $goods = $good->orderBy('weight')->get();
        return view($this->interface . '.good.index')->withGoods($goods);
    }

    public function category(Request $request, Category $category)
    {
        $category = $category->find($request->id);
        return view($this->interface . '.good.category')->withCategory($category);
    }

    public function create(Category $category)
    {
        $categories = $category->getCategories();
        return view($this->interface . '.good.create')->withCategories($categories);
    }

    public function store(Request $request, Good $good)
    {
        $validator = $this->storeValidation($request->all());
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $good->name = $request->name;
        $good->slugMaker = new Slug\NewSlugMaker($good, $request->slug);
        $good->slug = $good->slugMaker->slug;
        $good->title = $request->title;
        $good->desc = $request->desc;
        $good->keywords = $request->keywords;
        $good->text = $request->text;
        $good->category_id = $request->category;

        $max_weight = Good::where('category_id', $request->category)->max('weight');
        if ($max_weight === null) {
            $max_weight = 0;
        }

        $good->weight = ++$max_weight;
        $good->save();

        if ($request->file('image')) {
            foreach (json_decode($request->images_order) as $key => $value) {
                $file = $request->file('image.' . $value);
                if ($file) {
                    $image = new Image();
                    $image->extention = $file->getClientOriginalExtension();
                    $image->slugMaker = new Slug\ImageSlugMaker($image, $good->name);
                    $image->slug = $image->slugMaker->slug;
                    $image->alt = $request->alt[$key];
                    $image->weight = $key;
                    Storage::disk('images')->put($image->slug, File::get($file));
                    $good->images()->save($image);
                }
            }
        }

        if ($request->parameter) {
            $good->parameters()->attach(array_keys($request->parameter));
        }

        if ($request->cat) {
            return redirect()->route('good.category', ['cat' => $request->cat])->withStatus('Товар был успешно создан');
        }
        return redirect()->route('good.index')->withStatus('Товар был успешно создан');
    }

    public function storeValidation($data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:goods,slug',
            'title' => 'nullable|string|max:255',
            'keywords' => 'nullable|string|max:255',
            'desc' => 'nullable|string',
            'text' => 'nullable|string',
            'category' => 'required|integer|min:2|exists:categories,id',
            'image.*' => 'nullable|image'
        ]);
    }

    public function show(Request $request, Category $category, Good $good)
    {
        $categories = $category->getCategories();
        $good = $good->where('id', $request->id)->with('images')->first();

        return view($this->interface . '.good.show')->withCategories($categories)->withGood($good);
    }

    public function edit(Request $request, Good $good)
    {
        $validator = $this->editValidation($request->all());
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $good = $good->find($request->good_id);
        $good->name = $request->name;
        $good->slugMaker = new Slug\OldSlugMaker($good, $request->slug);
        $good->slug = $good->slugMaker->slug;
        $good->title = $request->title;
        $good->desc = $request->desc;
        $good->keywords = $request->keywords;
        $good->text = $request->text;
        $good->category_id = $request->category;
        $good->save();

        $images_order = json_decode($request->images_order);
        unset($images_order[0]);

        if ($good->images) {
            if ($images_order) {

                foreach ($good->images as $image) {
                    $isset = 0;
                    foreach ($images_order as $key => $value) {
                        if ($image->id == $value) {
                            $isset = 1;
                        }
                    }
                    if (!$isset) {
                        $image->delete();
                    }
                }

                foreach ($images_order as $key => $value) {
                    $image = Image::find($value);
                    if ($image) {
                        $image->alt = $request->alt[$value];
                        $image->weight = $key;
                        $image->save();
                    }
                }

            } else {
                $good->images()->delete();
            }
        }
        if ($request->file('image')) {
            foreach ($images_order as $key => $value) {
                $file = $request->file('image.' . $value);
                if ($file) {
                    $image = new Image();
                    $image->extention = $file->getClientOriginalExtension();
                    $image->slugMaker = new Slug\ImageSlugMaker($image, $good->name);
                    $image->slug = $image->slugMaker->slug;
                    $image->alt = $request->alt[$value];
                    $image->weight = $key;
                    Storage::disk('images')->put($image->slug, File::get($file));
                    $good->images()->save($image);
                }
            }
        }

        if ($request->parameter) {
            $good->parameters()->sync(array_keys($request->parameter));
        }

        if ($request->cat) {
            return redirect()->route('good.category', ['cat' => $request->cat])->withStatus('Товар был успешо изменён');
        }
        return redirect()->route('good.index')->withStatus('Товар был успешо изменён');
    }

    public function editValidation($data)
    {
        return Validator::make($data, [
            'good_id' => 'required|integer|exists:goods,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:goods,slug,' . $data['good_id'] . ',id',
            'title' => 'nullable|string|max:255',
            'keywords' => 'nullable|string|max:255',
            'desc' => 'nullable|string',
            'text' => 'nullable|string',
            'category' => 'required|integer|min:2|exists:categories,id',
            'image.*' => 'nullable|image'
        ]);
    }

    public function delete(Request $request, Good $good)
    {
        $validator = $this->deleteValidation($request->all());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $good = $good->find($request->delete_good_id);
        $good->parameters()->detach();
        $good->delete();

        if ($request->cat) {
            return redirect()->route('good.category', ['cat' => $request->cat])->withStatus('Товар был успешно удален');
        }
        return redirect()->route('good.index')->withStatus('Товар был успешно удален');
    }

    public function deleteValidation($data)
    {
        return Validator::make($data, [
            'delete_good_id' => 'required|integer|exists:goods,id'
        ]);
    }

    public function getCategoryFilters(Request $request, Category $category, Good $good)
    {
        $category = $category->find($request->category);
        $good = null;
        if ($request->good) {
            $good = $good->find($request->good);
        }

        return response()->json(['filters' => (String)view('admin.good.parts.filters', ['category' => $category, 'good' => $good])]);
    }
}
