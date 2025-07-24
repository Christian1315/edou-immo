<div>
    <!-- TABLEAU DE LISTE -->
    <div class="row mb-5">
        <div class="col-md-3"></div>
        <div class="col-6">
            <p class="text-center">Selectionnez une date pour filtrer les locataires <strong class="text-red">ayant payés</strong> </p>
            <form action="{{route('recovery_quelconque_date.FiltreByDateInAgency',crypId($agency->id))}}" method="POST">
                @csrf
                <input type="date" _required name="date" class="form-control">
                <button class="btn btn sm bg-red w-100"><i class="bi bi-filter-square"></i> Filtrer</button>
            </form>
        </div>
        <div class="col-md-3"></div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <p class="text">Liste des locataires ayant payé à la date : <strong class="text-red"> {{\Carbon\Carbon::parse(session()->get("any_date"))->locale('fr')->isoFormat('D MMMM YYYY') }}  </strong> </p>
            <h4 class="">Total: <strong class="text-red"> {{count($locators)}} </strong> </h4>
            <div class="table-responsive table-responsive-list shadow-lg">
                <table id="myTable" class="table table-striped table-sm">
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Nom</th>
                            <th class="text-center">Prénom</th>
                            <th class="text-center">Phone</th>
                            <th class="text-center">Adresse</th>
                            <th class="text-center">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(session("locators")?session()->get("locators"):$locators as $locator)
                        <tr class="align-items-center">
                            <td class="text-center">{{$loop->index+1}}</td>
                            <td class="text-center"><span class=" bg-light text-dark"> {{$locator["name"]}}</span></td>
                            <td class="text-center"><span class=" bg-light text-dark"> {{$locator["prenom"]}}</span></td>
                            <td class="text-center"><span class=" bg-light text-dark"> {{$locator["phone"]}}</span></td>
                            <td class="text-center"><span class=" bg-light text-dark"> {{$locator["adresse"]}}</span></td>
                            <td class="text-center"><span class=" bg-light text-dark"> {{$locator["email"]}}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>