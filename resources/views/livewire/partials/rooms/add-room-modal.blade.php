<!-- ADD ROOM -->
@can("room.create")
<div class="modal fade" id="addRoom" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <p class="">Ajout d'une Chambre</p>
                <button type="button" class="btn btn-sm text-red" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i></button>
            </div>
            <div class="modal-body">
                <form action="{{route('room._AddRoom')}}" method="POST" class="shadow-lg p-3 animate__animated animate__bounce" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="" class="d-block">Loyer</label>
                                <input type="number" name="loyer" value="{{old('loyer')}}" placeholder="Le loyer" class="form-control">
                                @error("loyer")
                                <span class="text-red">{{$message}}</span>
                                @enderror
                            </div><br>
                            <div class="mb-3">
                                <label for="" class="d-block">Numéro de chambre</label>
                                <input type="text" value="{{old('number')}}" name="number" placeholder="Numéro de la chambre" class="form-control">
                                @error("number")
                                <span class="text-red">{{$message}}</span>
                                @enderror
                            </div><br>
                            <div class="mb-3">
                                <label for="" class="d-block">Gardiennage</label>
                                <input type="number" value="{{old('gardiennage')}}" name="gardiennage" placeholder="Gardiennage ..." class="form-control">
                                @error("gardiennage")
                                <span class="text-red">{{$message}}</span>
                                @enderror
                            </div><br>
                            <div class="mb-3">
                                <label for="" class="d-block">Ordures</label>
                                <input type="number" value="{{old('rubbish')}}" name="rubbish" placeholder="Les ordures ..." class="form-control">
                                @error("rubbish")
                                <span class="text-red">{{$message}}</span>
                                @enderror
                            </div><br>
                            <div class="mb-3">
                                <label for="" class="d-block">Vidange</label>
                                <input type="number" value="{{old('vidange')}}" name="vidange" placeholder="La vidange ..." class="form-control">
                                @error("vidange")
                                <span class="text-red">{{$message}}</span>
                                @enderror
                            </div><br>
                            <div class="mb-3">
                                <span class=""> Photo de la chambre </span>
                                <input value="{{old('photo')}}" type="file" name="photo" class="form-control">
                                @error("photo")
                                <span class="text-red">{{$message}}</span>
                                @enderror
                            </div><br>

                            <div class="mb-3">
                                <input  onclick="showWaterInfo()" type="checkbox" name="water" class="btn-check" id="showWaterInfo">
                                <label  onclick="showWaterInfo()" class="btn bg-dark" for="showWaterInfo">
                                    Eau ... <br>
                                </label>
                            </div><br>

                            <div class="water shadow-lg roundered p-2" id="show_water_info">
                                <div class="form-check">
                                    <input onclick="waterDiscounterInputs()" name="water_discounter" class="form-check-input" type="checkbox" id="water_discounter">
                                    <label onclick="waterDiscounterInputs()" for="water_discounter" class="form-check-label">
                                        Décompteur
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input  name="water_conventionnal_counter" class="form-check-input" type="checkbox" id="showWaterConventionnalCounterInputs">
                                    <label onclick="showWaterConventionnalCounterInputs()" for="showWaterConventionnalCounterInputs" class="form-check-label" for="flexCheckChecked">
                                        Compteur Conventionnel
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input onclick="showForageInputs()" name="forage" class="form-check-input" type="checkbox" id="forage">
                                    <label onclick="showForageInputs()" class="form-check-label" for="forage">
                                        Forage
                                    </label>
                                </div>

                                <div class="mb-3" id="show_forage_inputs">
                                    <span for="" class="d-block">Forfait forage</span>
                                    <input type="number" value="{{old('forfait_forage')}}" name="forfait_forage" placeholder="Forfait forage" class="form-control" id="forfait_forage">
                                    @error("forfait_forage")
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="mb-3" id="water_discounter_inputs">
                                    <div class="form-group mb-3">
                                        <span for="" class="d-block">Prix unitaire par mêtre cube</span>
                                        <input value="{{old('unit_price')}}" type="number" name="unit_price" placeholder="Prix unitaire en mèttre cube" class="form-control" id="unit_price">
                                        @error("unit_price")
                                        <span class="text-red">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <span for="" class="d-block">Index début compteur d'eau</span>
                                        <input value="{{old('water_counter_start_index')?old('water_counter_start_index'):0}}" type="number" name="water_counter_start_index" placeholder="Index début ...." class="form-control" id="water_counter_start_index">
                                        @error("water_counter_start_index")
                                        <span class="text-red">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3" id="show_water_conventionnal_counter_inputs" >
                                    <span for="" class="d-block">Numéro du compteur</span>
                                    <input value="{{old('water_counter_number')}}" type="text" name="water_counter_number" placeholder="Numéro compteur" class="form-control" id="water_counter_number">
                                    @error("water_counter_number")
                                    <span class="text-red">{{$message}}</span>
                                    @enderror

                                    <div class="">
                                        <span for="" class="d-block">Index début conventionnel </span>
                                        <input value="{{old('water_conventionnel_counter_start_index')?old('water_conventionnel_counter_start_index'):0}}" type="number" name="water_conventionnel_counter_start_index" placeholder="Index début ...." class="form-control" id="water_conventionnel_counter_start_index">
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
                                <input type="number" value="{{old('cleaning')}}" name="cleaning" placeholder="Le nettoyage ..." class="form-control">
                                @error("cleaning")
                                <span class="text-red">{{$message}}</span>
                                @enderror
                            </div><br>
                            <div class="mb-3">
                                <label for="" class="d-block">Commentaire</label>
                                <textarea value="{{old('comments')}}" name="comments" rows="1" placeholder="Laisser un commentaire ..." class="form-control" class="form-control" id=""></textarea>
                                @error("comments")
                                <span class="text-red">{{$message}}</span>
                                @enderror
                            </div><br>
                            <div class="mb-3">
                                <label for="" class="d-block">Maison</label>
                                <select value="{{old('house')}}" class="form-select form-control agency-modal-select2" name="house" aria-label="Default select example">
                                    @foreach($houses as $house)
                                    <option value="{{$house['id']}}">{{$house['name']}}</option>
                                    @endforeach
                                </select>
                                @error("house")
                                <span class="text-red">{{$message}}</span>
                                @enderror
                            </div><br>
                            <div class="mb-3">
                                <label for="" class="d-block">Nature</label>
                                <select value="{{old('nature')}}" class="form-select form-control agency-modal-select2" name="nature" aria-label="Default select example">
                                    @foreach($room_natures as $nature)
                                    <option value="{{$nature['id']}}">{{$nature['name']}}</option>
                                    @endforeach
                                </select>
                                @error("nature")
                                <span class="text-red">{{$message}}</span>
                                @enderror
                            </div><br>
                            <div class="mb-3">
                                <label for="" class="d-block">Type</label>
                                <select value="{{old('type')}}" class="form-select form-control agency-modal-select2" name="type" aria-label="Default select example">
                                    @foreach($room_types as $type)
                                    <option value="{{$type['id']}}">{{$type['name']}}</option>
                                    @endforeach
                                </select>
                                @error("nature")
                                <span class="text-red">{{$message}}</span>
                                @enderror
                            </div><br>

                            <div class="mb-3">
                                <input onclick="showElectricityInfo()" type="checkbox" name="electricity" class="btn-check" id="btncheck_electricity">
                                <label onclick="showElectricityInfo()" class="btn bg-dark" for="btncheck_electricity">
                                    Electricité ... <br>
                                </label>
                            </div><br>

                            <div class="electricity shadow-lg roundered p-2" id="showElectricityInfo_block">
                                <div class="form-check">
                                    <input onclick="showElectricityDiscountInputs()" name="electricity_discounter" class="form-check-input" type="checkbox" id="electricity_decounter_flexCheckChecked">
                                    <label onclick="showElectricityDiscountInputs()" for="electricity_decounter_flexCheckChecked" class="form-check-label" for="electricity_decounter_flexCheckChecked">
                                        Décompteur
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input name="electricity_card_counter" class="form-check-input" type="checkbox" id="electricity_card_flexCheckDefault">
                                    <label class="form-check-label" for="electricity_card_flexCheckDefault">
                                        Compteur à carte
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input name="electricity_conventionnal_counter" class="form-check-input" type="checkbox" id="electricity_card_conven_flexCheckChecked">
                                    <label class="form-check-label" for="electricity_card_conven_flexCheckChecked">
                                        Compteur Conventionnel
                                    </label>
                                </div>

                                <div id="show_electricity_discountInputs">
                                    <div class="mb-3">
                                        <span for="" class="d-block">Numéro compteur</span>
                                        <input value="{{old('electricity_counter_number')}}" type="text" name="electricity_counter_number" placeholder="Numéro compteur" class="form-control" id="electricity_counter_number">
                                        @error("electricity_counter_number")
                                        <span class="text-red">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <span for="" class="d-block">Prix unitaire</span>
                                        <input value="{{old('electricity_unit_price')}}" type="text" name="electricity_unit_price" placeholder="Prix unitaire par kilowatheure " class="form-control" id="electricity_unit_price">
                                        @error("electricity_unit_price")
                                        <span class="text-red">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <span for="" class="d-block">Index de début</span>
                                        <input value="{{old('electricity_counter_start_index')}}" type="text" name="electricity_counter_start_index" placeholder="Index début ...." class="form-control" id="electricity_counter_start_index">
                                        @error("electricity_counter_start_index")
                                        <span class="text-red">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="w-100 btn-sm btn bg-red"><i class="bi bi-check-circle-fill"></i> Enregistrer</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
@endcan