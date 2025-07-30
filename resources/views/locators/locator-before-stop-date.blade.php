<x-templates.agency :title="'Locataires'" :active="'statistique'" :agency="$house->_Agency">

    <!-- HEADER -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Locataires <span class="text-red">ayant payés avant</span> arrêt des états</h1>
    </div>
    <br>

    <div class="row">
        <div class="col-md-12">
            <h3 class="">Maison : {{$house['name']}} </h3>
            <br>
            <h6 class=""> Montant total: <b class="text-red"> {{number_format($locationsFiltered["beforeStopDateTotal_to_paid"],0,","," ")}} fcfa</b> </h6>

            <!-- Imprimer -->
            <form action="{{route('location.FiltreBeforeStateDateStoped',crypId($house->id))}}" method="get">
                @csrf
                <input type="hidden" name="imprimer" value="imprimer">
                <button class="btn bg-light border text-dark my-5"><i class="bi bi-filetype-pdf"></i> Imprimer</button>
            </form>

            <div class="table-responsive shadow-lg p-3">
                <table id="myTable" class="table table-striped table-sm p-3">
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Nom</th>
                            <th class="text-center">Prénom</th>
                            <th class="text-center">Phone </th>
                            <th class="text-center">Adresse</th>
                            <th class="text-center">Mois</th>
                            <th class="text-center">Montant payé</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($locationsFiltered['beforeStopDate']))
                        @foreach($locationsFiltered['beforeStopDate'] as $locator)
                        <tr class="align-items-center">
                            <td class="text-center">{{$loop->index + 1}}</td>
                            <td class="text-center"><span class="badge bg-light text-dark"> {{$locator["name"]}}</span></td>
                            <td class="text-center"><span class="badge bg-light text-dark"> {{$locator["prenom"]}}</span></td>
                            <td class="text-center"><span class="badge bg-light text-dark"> {{$locator["phone"]}}</span></td>
                            <td class="text-center"><span class="badge bg-light text-dark"> {{$locator["adresse"]}}</span></td>
                            <td class="text-center"> <span class="badge bg-light text-red"><i class="bi bi-calendar2-check"></i> {{\Carbon\Carbon::parse($locator["month"])->locale('fr')->isoFormat('D MMMM YYYY')}}</span> </td>
                            <td class="text-center"> <span class="badge bg-light text-red">{{number_format($locator["amount_paid"],0,","," ")}}</span> </td>
                        </tr>
                        @endforeach

                        @else
                        <p class="text-center text-red">Aucun locataire!</p>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-templates.agency>