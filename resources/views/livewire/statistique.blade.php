<div>
    <!-- TABLEAU DE LISTE -->
    <h4 class="">Total: <strong class="text-red"> {{count($houses)}} </strong> </h4>
    <div class="row">
        <div class="col-12">
            <div class="table-responsive table-responsive-list shadow-lg p-3">
                <table id="myTable" class="table table-striped table-sm">
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Nom</th>
                            <th class="text-center">Latitude</th>
                            <th class="text-center">Longitude</th>
                            <th class="text-center">Type de maison</th>
                            <th class="text-center">Superviseur</th>
                            <th class="text-center">Propriétaire</th>
                            <th class="text-center">Mouvements</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($houses as $house)
                        <tr class="align-items-center">
                            <td class="text-center">{{$loop->index + 1}}</td>
                            <td class="text-center text-red"> <span class=" bg-light text-dark">{{$house["name"]}} </span> </td>
                            <td class="text-center"> <span class=" bg-light text-dark">@if($house["latitude"]) {{$house["latitude"]}} @else --- @endif </span> </td>
                            <td class="text-center"><span class=" bg-light text-dark"> @if($house["longitude"]) {{$house["longitude"]}} @else --- @endif</span></td>
                            <td class="text-center"><span class=" bg-light text-dark"> {{$house["Type"]["name"]}}</span></td>
                            <td class="text-center text-red"> <span class=" bg-light text-dark"> {{$house["Supervisor"]["name"]}}</span> </td>
                            <td class="text-center"> <span class=" bg-light text-dark"> {{$house["Proprietor"]["lastname"]}} {{$house["Proprietor"]["firstname"]}}</span> </td>
                            <td class="text-center">
                                <a  href="{{route('location.FiltreBeforeStateDateStoped', crypId($house['id']))}}" class="btn btn-sm btn-dark" title="Avant arrêt des états"><i class="bi bi-arrow-left-circle-fill"></i></a> &nbsp;
                                <a  href="{{route('location.FiltreAfterStateDateStoped', crypId($house['id']))}}" class="btn btn-sm bg-red" title="Après arrêt des états"><i class="bi bi-arrow-right-circle-fill"></i></a> 
                                &nbsp;
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>