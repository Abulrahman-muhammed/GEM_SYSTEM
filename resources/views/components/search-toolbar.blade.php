<form method="GET" action="{{ $action }}">

    <div class="input-group">

        <div class="input-group-prepend">
            <span class="input-group-text">
                <i class="fe fe-search"></i>
            </span>
            <!--reset link -->
            @if(request('search'))
                <a href="{{ $action }}?{{ http_build_query(request()->except('search', 'page','per_page')) }}" class="btn btn-light">
                    <i class="fe fe-refresh-cw"></i>
                </a>
            @endif
        </div>

        <input
            type="text"
            name="search"
            class="form-control"
            placeholder="{{ $placeholder ?? 'Search...' }}"
            value="{{ request('search') }}">
        

        <select
            name="per_page"
            class="custom-select"
            style="max-width: 90px;"
            onchange="this.form.submit()">

            @foreach([12,32,64,128] as $size)
                <option
                    value="{{ $size }}"
                    {{ request('per_page', 32) == $size ? 'selected' : '' }}>
                    {{ $size }}
                </option>
            @endforeach

        </select>
        
    </div>

</form>