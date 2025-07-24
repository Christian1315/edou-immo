<div>
    <h6 class="">caisse :<em class="text-red"> {{$Account["name"]}} </em> </h6>
    <table id="myTable" class="table table-striped table-sm">
        <thead class="bg_dark">
            <tr>
                <th class="text-center">N°</th>
                <th class="text-center">Ancien solde</th>
                <th class="text-center">Crédité(s)</th>
                <th class="text-center">Débité(s)</th>
                <th class="text-center">Nouveau solde</th>
                <th class="text-center">Description</th>
                <th class="text-center">Status</th>
                <th class="text-center">Fait le:</th>
            </tr>
        </thead>
        <tbody>
            @foreach($agencyAccountsSolds as $sold)
            <tr class="align-items-center">
                <td class="text-center">{{$loop->index+1}}</td>
                <td class="text-center">
                    <span class=" bg-dark text-white ">{{$sold["old_sold"]? number_format($sold["old_sold"],0,","," ") :0}}</span>
                </td>
                <td class="text-center"><span class=" bg-light  text-red"> {{number_format($sold["sold_added"]?$sold["sold_added"]:0,0,","," ")}}</span> </td>
                <td class="text-center"><span class=" bg-light text-dark"> {{number_format($sold["sold_retrieved"]?$sold["sold_retrieved"]:0,0,","," ")}}</span> </td>
                <td class="text-center">
                    <span class=" bg-success text-white"> {{number_format($sold["sold"],0,","," ")}}</span>
                </td>
                <td class="text-center">
                    <textarea name="" rows="1" id="" class="form-control">{{$sold["description"]}}</textarea>
                </td>
                <td class="text-center">
                    <span class=" bg-light @if($sold->visible) text-success @else text-dark @endif ">@if($sold->visible) Actuel @else Ancien @endif</span>
                </td>
                <td class="text-center">
                    <strong class=" bg-light text-red"> {{\Carbon\Carbon::parse($sold["created_at"])->locale('fr')->isoFormat('D MMMM YYYY')}}</strong>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>