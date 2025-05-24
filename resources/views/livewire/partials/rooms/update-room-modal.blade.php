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

                        </div>
                        <!--  -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="" class="d-block">Nettoyage</label>
                                <input type="text" id="cleaning" name="cleaning" placeholder="Le nettoyage ..." class="form-control">

                            </div><br>
                            <!-- <div id="">
                                <div class="mb-3">
                                    <span for="" class="d-block">Numéro compteur electricité</span>
                                    <input id="electricity_counter_number" type="text" name="electricity_counter_number" placeholder="Numéro compteur" class="form-control" id="">
                                </div>
                            </div><br>
                             -->
                            <div class="mb-3">
                                <label for="" class="d-block">Ordures</label>
                                <input type="text" id="rubbish" name="rubbish" placeholder="Les ordures ..." class="form-control">
                            </div><br>

                            <div class="mb-3">
                                <label for="" class="d-block">Vidange</label>
                                <input type="text" id="vidange" name="vidange" placeholder="La vidange ..." class="form-control">

                            </div><br>

                            <!-- <div class="mb-3">
                                <span for="" class="d-block">Prix unitaire d'électricité</span>
                                <input id="update_electricity_unit_price" type="text" name="electricity_unit_price" class="form-control">
                            </div> <br>
                            <div class="mb-3" id="water_discounter_inputs">
                                <span for="" class="d-block">Prix unitaire d'eau</span>
                                <input id="update_unit_price" type="text" name="unit_price" class="form-control">
                            </div> -->
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