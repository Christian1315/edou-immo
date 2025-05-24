<div class="alert" id="alert">
    <div class="row mb-3">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            @if (session()->has('success'))
            <div class="alert bg-dark text-white text-center">
                <button type="button" class="btn btn-sm text-red float-right" data-bs-dismiss="alert"><i class="bi bi-x-circle"></i></button>
                {{ session()->get('success')}}
            </div>
            @endif

            @if (session()->has('error'))
            <div class="alert bg-red text-white text-center">
                <button type="button" class="btn btn-sm text-red float-right" data-bs-dismiss="alert"><i class="bi bi-x-circle"></i></button>
                {{ session()->get('error')}}
            </div>
            @endif
        </div>
        <div class="col-md-4"></div>
    </div>

    <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
            <div class="">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <button type="button" class="btn btn-sm text-red float-right" data-bs-dismiss="alert"><i class="bi bi-x-circle"></i></button>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
        <div class="col-2"></div>
    </div>

    @include('sweetalert::alert')

</div>