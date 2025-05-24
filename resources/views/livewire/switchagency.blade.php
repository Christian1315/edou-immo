<div>
    <div class="row">
        <div class="col-md-12">
            <p class="">Cliquez ici pour naviguer dans les agences <i class="bi bi-hand-thumbs-down-fill"></i></p>

            <ul class="d-flex" style="list-style:none;">
                @foreach($agencies as $agency)
                <li class="page-item shadow m-2 roundered"><a class="page-link  bg-light text-dark" href="/{{crypId($agency['id'])}}/manage-agency"> <i class="bi bi-house-up"></i> {{$agency["name"]}}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>