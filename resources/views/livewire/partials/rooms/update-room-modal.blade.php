@can("room.edit")
<!-- ###### MODEL DE MODIFICATION ###### -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fs-5" id="exampleModalLabel">Modifier <strong> <em class="text-red" id="update_room_fullname"></em> </strong> </h6>
            </div>
            <div class="modal-body">
                <form id="update_form" method="POST" class="shadow-lg p-3 animate__animated animate__bounce" enctype="multipart/form-data">
                    @csrf
                    @method("PATCH")
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="" class="d-block">Loyer</label>
                                <input type="text" name="loyer" id="loyer" placeholder="Le loyer" class="form-control">

                            </div><br>
                            <div class="mb-3">
                                <label for="" class="d-block">Numéro de chambre</label>
                                <input type="text" id="number" name="number" placeholder="Numéro de la chambre" class="form-control">

                            </div><br>
                            <div class="mb-3">
                                <label for="" class="d-block">Gardiennage</label>
                                <input type="text" id="gardiennage" name="gardiennage" placeholder="Gardiennage ..." class="form-control">

                            </div><br>

                            <div class="mb-3">
                                <input type="checkbox" name="water" class="btn-check" id="update_showWaterInfo">
                                <label class="btn bg-dark" for="update_showWaterInfo">
                                    Eau ... <br>
                                </label>
                            </div><br>

                            <div class="water shadow-lg roundered p-2" id="update_show_water_info">
                                <div class="form-check">
                                    <input name="water_discounter" class="form-check-input" type="checkbox" id="update_water_discounter">
                                    <label for="update_water_discounter" class="form-check-label">
                                        Décompteur
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input name="water_conventionnal_counter" class="form-check-input" type="checkbox" id="update_showWaterConventionnalCounterInputs">
                                    <label for="update_showWaterConventionnalCounterInputs" class="form-check-label" for="flexCheckChecked">
                                        Compteur Conventionnel
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input name="forage" class="form-check-input" type="checkbox" id="update_forage">
                                    <label class="form-check-label" for="update_forage">
                                        Forage
                                    </label>
                                </div>

                                <div class="mb-3" id="update_show_forage_inputs">
                                    <span for="" class="d-block">Forfait forage</span>
                                    <input type="number" value="{{old('forfait_forage')}}" name="forfait_forage" placeholder="Forfait forage" class="form-control" id="update_forfait_forage">
                                    @error("forfait_forage")
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="mb-3" id="update_water_discounter_inputs">
                                    <div class="form-group mb-3">
                                        <span for="" class="d-block">Prix unitaire par mêtre cube</span>
                                        <input value="{{old('unit_price')}}" type="number" name="unit_price" placeholder="Prix unitaire en mèttre cube" class="form-control" id="update_unit_price">
                                        @error("unit_price")
                                        <span class="text-red">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <span for="" class="d-block">Index début compteur d'eau</span>
                                        <input type="number" name="water_counter_start_index" placeholder="Index début ...." class="form-control" id="update_water_counter_start_index">
                                        @error("water_counter_start_index")
                                        <span class="text-red">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3" id="update_show_water_conventionnal_counter_inputs">
                                    <span for="" class="d-block">Numéro du compteur</span>
                                    <input value="{{old('water_counter_number')}}" type="text" name="water_counter_number" placeholder="Numéro compteur" class="form-control" id="update_water_counter_number">
                                    @error("water_counter_number")
                                    <span class="text-red">{{$message}}</span>
                                    @enderror

                                    <div class="">
                                        <span for="" class="d-block">Index début conventionnel </span>
                                        <input value="" type="number" name="water_conventionnel_counter_start_index" placeholder="Index début ...." class="form-control" id="update_water_conventionnel_counter_start_index">
                                        @error("water_conventionnel_counter_start_index")
                                        <span class="text-red">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--  -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="" class="d-block">Nettoyage</label>
                                <input type="text" id="update_cleaning" name="cleaning" placeholder="Le nettoyage ..." class="form-control">

                            </div><br>
                            <div class="mb-3">
                                <label for="" class="d-block">Ordures</label>
                                <input type="text" id="update_rubbish" name="rubbish" placeholder="Les ordures ..." class="form-control">
                            </div><br>

                            <div class="mb-3">
                                <label for="" class="d-block">Vidange</label>
                                <input type="text" id="update_vidange" name="vidange" placeholder="La vidange ..." class="form-control">

                            </div><br>

                            <div class="mb-3">
                                <input type="checkbox" name="electricity" class="btn-check" id="update_btncheck_electricity">
                                <label class="btn bg-dark" for="update_btncheck_electricity">
                                    Electricité ... <br>
                                </label>
                            </div><br>

                            <div class="electricity shadow-lg roundered p-2" id="updateShowElectricityInfo_block">
                                <div class="form-check">
                                    <input name="electricity_discounter" class="form-check-input" type="checkbox" id="update_electricity_decounter_flexCheckChecked">
                                    <label for="update_electricity_decounter_flexCheckChecked" class="form-check-label" for="update_electricity_decounter_flexCheckChecked">
                                        Décompteur
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input name="electricity_card_counter" class="form-check-input" type="checkbox" id="update_electricity_card_flexCheckDefault">
                                    <label class="form-check-label" for="update_electricity_card_flexCheckDefault">
                                        Compteur à carte
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input name="electricity_conventionnal_counter" class="form-check-input" type="checkbox" id="update_electricity_card_conven_flexCheckChecked">
                                    <label class="form-check-label" for="update_electricity_card_conven_flexCheckChecked">
                                        Compteur Conventionnel
                                    </label>
                                </div>

                                <div id="update_show_electricity_discountInputs">
                                    <div class="mb-3">
                                        <span for="" class="d-block">Numéro compteur</span>
                                        <input value="{{old('electricity_counter_number')}}" type="text" name="electricity_counter_number" placeholder="Numéro compteur" class="form-control" id="update_electricity_counter_number">
                                        @error("electricity_counter_number")
                                        <span class="text-red">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <span for="" class="d-block">Prix unitaire</span>
                                        <input value="{{old('electricity_unit_price')}}" type="text" name="electricity_unit_price" placeholder="Prix unitaire par kilowatheure " class="form-control" id="update_electricity_unit_price">
                                        @error("electricity_unit_price")
                                        <span class="text-red">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <span for="" class="d-block">Index de début</span>
                                        <input value="{{old('electricity_counter_start_index')}}" type="text" name="electricity_counter_start_index" placeholder="Index début ...." class="form-control" id="update_electricity_counter_start_index">
                                        @error("electricity_counter_start_index")
                                        <span class="text-red">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm bg-red"><i class="bi bi-check-circle"></i> Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan