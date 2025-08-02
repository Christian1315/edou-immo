<div>
    <div>
        <div class="d-flex header-bar">
            <h2 class="accordion-header">
                <button type="button" class="btn btn-sm bg-dark" data-bs-toggle="modal" data-bs-target="#add_agency">
                    <i class="bi bi-cloud-plus-fill"></i> Ajouter
                </button>
            </h2>
        </div>
    </div>

    @if($showCautions)
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="alert bg-dark text-white">
                    Cautions générées avec succès! Cliquez sur le lien ci-dessous pour la télécharger: <br>
                    <a class="text-red" href="{{$cautions_link}}" rel="noopener noreferrer">Télécharger</a>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($showPrestations)
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="alert bg-dark text-white">
                    Statistique des prestations générées avec succès! Cliquez sur le lien ci-dessous pour la télécharger: <br>
                    <a class="text-red" href="{{$cautions_link}}" rel="noopener noreferrer">Télécharger</a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="modal fade" id="add_agency" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="">Ajout d'une nouvelle agence</p>
                    <button type="button" class="btn btn-sm text-red" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('AddAgency')}}" method="POST" class="shadow-lg p-3 animate__animated animate__bounce" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="text" value="{{old('name')}}" name="name" placeholder="Nom de l'agence" class="form-control">
                                    @error("name")
                                    <span class="text-red"> {{$message}} </span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <input type="text" value="{{old('ifu')}}" name="ifu" placeholder="Ifu de l'agence" class="form-control">
                                    @error("ifu")
                                    <span class="text-red"> {{$message}} </span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <input type="text" value="{{old('rccm')}}" name="rccm" placeholder="Rccm de l'agence" class="form-control">
                                    @error("rccm")
                                    <span class="text-red"> {{$message}} </span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <span class="">Fichier ifu de l'agence</span>
                                    <input type="file" value="{{ old('ifu_file') }}" name="ifu_file" placeholder="Fichier ifu de l'agence" class="form-control">
                                    @error("ifu_file")
                                    <span class="text-red"> {{$message}} </span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <select value="{{old('country')}}" class="form-select form-control agency-modal-select2" name="country" aria-label="Default select example">
                                        <option>Pays</option>
                                        @foreach($countries as $countrie)
                                        @if($countrie->id==4)
                                        <option value="{{$countrie->id}}">{{$countrie->name}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    @error("country")
                                    <span class="text-red"> {{$message}} </span>
                                    @enderror
                                </div><br>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="phone" value="{{old('phone')}}" name="phone" placeholder="Téléphone de l'agence" class="form-control">
                                    @error("phone")
                                    <span class="text-red"> {{$message}} </span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <input type="text" value="{{old('email')}}" placeholder="Adresse email de l'agence" name="email" class="form-control">
                                    @error("email")
                                    <span class="text-red"> {{$message}} </span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <span class="">Fichier Rccm de l'agence</span>
                                    <input type="file" value="{{old('rccm_file')}}" name="rccm_file" placeholder="Fichier rccm de l'agence" class="form-control">
                                    @error("rccm_file")
                                    <span class="text-red"> {{$message}} </span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <select value="{{old('city')}}" class="form-control agency-modal-select2" name="city" aria-label="Default select example">
                                        <option>Ville</option>
                                        @foreach($cities as $citie)
                                        <option value="{{$citie->name}}">{{$citie->name}}</option>
                                        @endforeach
                                    </select>
                                    @error("city")
                                    <span class="text-red"> {{$message}} </span>
                                    @enderror
                                </div><br>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="w-100 btn-sm btn bg-red"><i class="bi bi-check-circle-fill"></i> Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>

    <!-- TABLEAU DE LISTE -->
    <h4 class="my-2">Total: <strong class="text-red"> {{$agencies->count()}} </strong> </h4>

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive table-responsive-list shadow-lg">
                <table id="myTable" class="table table-striped table-sm">
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Nom</th>
                            <th class="text-center">Téléphone</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">City</th>
                            <th class="text-center">Rccm</th>
                            <th class="text-center">Statistique</th>
                            <th class="text-center">Ifu</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    @if($agencies->count()>0)
                    <tbody>
                        @foreach($agencies as $agency)
                        <tr class="align-items-center text-center">
                            <td class="text-center">{{$loop->index+1}}</td>
                            <td class="text-center"><span class=" bg-light text-dark"> {{$agency["name"]}} </span></td>
                            <td class="text-center"><span class=" bg-light text-dark"> {{$agency["phone"]}} </span></td>
                            <td class="text-center"><span class=" bg-light text-dark"> {{$agency["email"]}} </span></td>
                            <td class="text-center"><span class=" bg-light text-dark"> {{$agency->city}} </span></td>
                            <td class="text-center">
                                @if($agency->rccm_file)
                                <a href="{{$agency->rccm_file}}" class="btn btn-sm text-danger" rel="noopener noreferrer"><i class="bi bi-eye"></i></a>
                                @else
                                ---
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="/{{$agency['id']}}/show_prestation_statistique" class="btn btn-sm bg-dark shadow-lg"><i class="bi bi-list-ol"></i> Prestation</a>
                            </td>
                            <td class="text-center"> <a href="{{$agency->ifu_file}}" class="text-danger" rel="noopener noreferrer"><i class="bi bi-eye"></i></a> </td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm bg-light text-red dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-hand-index-thumb"></i> Gérer
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="text-dark btn btn-sm" href="{{route('location._ManageCautions',crypId($agency->id))}}" class="shadow-lg"><i class="bi bi-file-earmark-pdf-fill"></i> Génerer les Cautions</a>
                                        </li>
                                        <li><a target="_blank" href="/{{crypId($agency['id'])}}/manage-agency" class="btn btn-sm bg-warning text-dark text-uppercase">
                                                <i class="bi bi-house-x-fill"></i> Gérer l'agence
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>