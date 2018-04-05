@forelse($category->filters()->orderBy('weight')->get() as $filter)
    <div class="col-md-6">
        <div class="row">
            <p>{{$filter->name}}</p>
        </div>
        @forelse($filter->parameters()->orderBy('weight')->get() as $parameter)
            <div class="row">
                <div class="col-md-10">
                    <p>{{$parameter->name}}</p>
                </div>
                <div class="col-md-2">
                    <input type="checkbox"
                           name="parameter[{{$parameter->id}}]" {{isset($good) && $good && $good->parameters->contains($parameter->id)?"checked":""}}>
                </div>
            </div>
        @empty

        @endforelse
    </div>
@empty

@endforelse