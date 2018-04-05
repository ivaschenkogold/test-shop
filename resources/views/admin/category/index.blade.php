@extends('layouts.admin_app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Категории
                        @if(isset($accesses->category->create))
                            <a href="{{route('category.create')}}" class="btn btn-info">Создать</a>
                        @endif
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                Упс! Что-то пошло не так<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="">
                            <ol class="{{isset($accesses->category->edit)?"sortcategory sortable ui-sortable":""}}">
                                @php
                                    $categories = $categories->toTree();

                                    $traverse = function ($categories, $prefix = '') use (&$traverse, $accesses) {
                                        echo $prefix;
                                        foreach ($categories as $category) {
                                            echo '<li id="list_'.$category->id.'">';

                                            echo '<div class="row"><span class="col-md-7">'.$category->name.'</span>';

                                            if(isset($accesses->category->show) || isset($accesses->category->delete) || isset($accesses->filter->index) || isset($accesses->good->index)){
                                                echo '<span class="category-buttons col-md-5">';
                                                if(isset($accesses->category->show)){
                                                    echo '<a href="'.route('category.show', ['id' => $category->id]).'" class="btn btn-primary">Просмотр</a>';
                                                }
                                                if(isset($accesses->filter->index)){
                                                    echo '<a href="'.route('filter.category', ['id' => $category->id]).'" class="btn btn-primary">Фильтры</a>';
                                                }
                                                if(isset($accesses->good->index)){
                                                    echo '<a href="'.route('good.category', ['id' => $category->id]).'" class="btn btn-primary">Товары</a>';
                                                }
                                                if(isset($accesses->category->delete)){
                                                    echo '<a class="btn btn-danger show_delete_category_modal" data-id="'.$category->id.'">Удалить</a>';
                                                }
                                                echo '</span>';
                                            }
                                            echo'</div>';

                                            if(!$category->children->isEmpty()){
                                                $traverse($category->children, '<ol>');
                                                echo '</ol>';
                                                echo '</li>';
                                            }else{
                                                echo '</li>';
                                            }

                                        }
                                    };
                                    $traverse($categories);
                                @endphp
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($accesses->category->delete))
        <!-- Modal -->
        <div id="delete_category_modal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Удалить категорию?</h4>
                    </div>
                    <form action="{{route('category.delete')}}" method="post">
                        @csrf
                        <input type="hidden" name="delete_category_id" id="delete_category_id" value="">
                        <div class="modal-body">
                            <div class="new-good-category" hidden>
                                <div class="row">
                                    <p>В этой группе находятся <span class="goods-in-category-count"></span> товаров.
                                        Для
                                        удаления категории необходимо перенести её товары в другую.</p>
                                    <div class="col-md-4">
                                        <label for="">Переместить товары в</label>
                                    </div>
                                    <div class="col-md-8 move-goods-to">
                                        <select name="move_goods_to" id="move_goods_to" class="form-control">
                                            @forelse($categories as $category)
                                                <option value="{{$category->id}}">
                                                    @for($i = 0; $i < $category->depth; $i++)
                                                        -
                                                    @endfor
                                                    {{$category->name}}
                                                </option>
                                            @empty

                                            @endforelse
                                        </select>
                                        <span class="help-block" hidden>
                                            <strong>У Вас нету категорий куда можно перенести товары, создайте новую категорию или удалите товары</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">Удалить</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    @endif
@endsection
