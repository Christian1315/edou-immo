<div>
    <!-- ENCAISSEMENT -->
    @can("caisses.credite")
    <button type="button" class="btn btn-sm bg-red text-white" data-bs-toggle="modal" data-bs-target="#encaisser">
        <i class="bi bi-currency-exchange"></i> Créditer
    </button>

    <!-- Modal -->
    <div class="modal fade" id="encaisser" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Créditer une caisse </h5>
                    <button type="button" class="btn btn-sm btn-light text-red" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-circle"></i></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('sold._CreditateAccount')}}" class="shadow-lg p-3 animate__animated animate__bounce" method="POST">
                        @csrf
                        <input type="hidden" name="agency" value="{{$agency->id}}" id="">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label>Choisissez la caisse en occurrence</label>
                                    <select name="agency_account" required class="form-select form-control agency-modal-select2" aria-label="Default select example">
                                        @foreach($agencyAccounts as $agency_account)
                                        @if($agency_account->_Account->id!=4 && $agency_account->_Account->id!=9 && $agency_account->_Account->id!=5)
                                        <option value="{{$agency_account->id}}" data-account="{{$agency_account->_Account->id}}"> {{$agency_account->_Account->name}} --- <em class="text-danger">( solde actuel: @if($agency_account->AgencyCurrentSold){{$agency_account->AgencyCurrentSold->sold}} @else 0 @endif)</em> </option>
                                        @endif
                                        @endforeach
                                    </select>

                                    @error("agency_account")
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <input type="number" name="sold" required value="{{old('sold')}}" placeholder="Précisez le montant ...." class="form-control" id="">
                                    @error("old")
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <textarea name="description" value="{{old('description')}}" rows="1" class="form-control" placeholder="La description ...."></textarea>
                                    @error("description")
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="w-100 btn bg-red btn-sm"> <i class="bi bi-currency-exchange"></i> Encaisser</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endcan

    <!-- DECAISSEMENT -->
    @can("caisses.decredite")
    <button type="button" class="btn btn-sm bg-red text-white" data-bs-toggle="modal" data-bs-target="#decaisser">
        <i class="bi bi-currency-exchange"></i> Décaisser
    </button>

    <!-- Modal -->
    <div class="modal fade" id="decaisser" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Décaisser une caisse </h5>
                    <button type="button" class="btn btn-sm btn-light text-red" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-circle"></i></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('sold._DeCreditateAccount')}}" method="POST" class="shadow-lg p-3 animate__animated animate__bounce">
                        @csrf
                        <h5 class="">Décaisser une caisse</h5>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label>Choisissez la caisse en occurrence</label>
                                    <select id="caisses" name="agency_account" class="form-select form-control agency-modal-select2" aria-label="Default select example">
                                        @foreach($agencyAccounts as $agency_account)
                                        @if($agency_account->_Account->id!=4 && $agency_account->_Account->id!=9 && $agency_account->_Account->id!=5)
                                        <!-- seul un admin ou un master peut crediter la caisse CDR -->
                                        <option value="{{$agency_account->id}}" data-account="{{$agency_account->_Account->id}}">{{$agency_account->_Account->name}} --- <em class="text-danger">( solde actuel: @if($agency_account->AgencyCurrentSold){{$agency_account->AgencyCurrentSold->sold}} @else 0 @endif)</em> </option>
                                        @endif
                                        @endforeach
                                    </select>
                                    @error("agency_account")
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 cdr_caisse d-none">
                                    <span class="text-red"> Ce champ est réquis seulement pour la caisse <em class="text-red"> CDR </em> </span>
                                    <select name="house" class="form-select form-control agency-modal-select2" aria-label="Default select example">
                                        <option>**Choisir une maison</option>
                                        @foreach($houses as $house)
                                        <option value="{{$house['id']}}">{{$house["name"]}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <input type="number" name="sold" required value="{{old('sold')}}" placeholder="Précisez le montant ...." class="form-control" id="">
                                    @error("old")
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <textarea name="description" required value="{{old('description')}}" rows="1" class="form-control" placeholder="La description ...."></textarea>
                                    @error("description")
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="w-100 btn bg-red btn-sm"><i class="bi bi-check-circle"></i> Décaisser</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endcan

    <br><br>
    <style>
        input {
            font-weight: bold !important;
            font-size: 30px;
        }
    </style>

    <h4 class="">Total: {{count($agencyAccounts)}} </h4>
    <div class="row">
        @foreach($agencyAccounts as $agency_account)
        <div class="col-sm-3">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="card-title text-red">{{$agency_account['_Account']['name']}}</h5>
                    <p class="card-text">{{substr($agency_account['_Account']['description'],0,20)}} ...</p>
                    <h5 class="">Plafond: <strong class="text-red"> {{number_format($agency_account['_Account']["plafond_max"],0,","," ")}}</strong> </h5>

                    @if($agency_account->AgencyCurrentSold)
                    <input disabled type="text" class="form-control" value="Solde : {{$agency_account->AgencyCurrentSold->sold}}">
                    @else
                    <input disabled type="text" class="form-control" value="Solde : 0">
                    @endif

                    <br>
                    <a href="/{{crypId($agency['id'])}}/{{$agency_account['id']}}/caisse-mouvements" class="btn btn-sm bg-red">Mouvements</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @push("scripts")
    <script type="text/javascript">
        $(document).ready(function() {
            $('#caisses').on('select2:select', function(e) {
                // alert(e.params.data.id); // ou e.params.data.text selon ce que tu veux
                const selected = $(this).find(':selected');
                const account = selected.data('account');

                if (account == 3) {
                    $(".cdr_caisse").removeClass("d-none")
                } else {
                    $(".cdr_caisse").addClass("d-none")
                }
            });
        })
    </script>
    @endpush
</div>