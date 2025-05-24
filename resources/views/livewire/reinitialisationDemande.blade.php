<div>

    @if($demand)
    <form action="{{roiute('demandeReinitialisation')}}" method="POST" class="shadow-lg p-3 roundered bg-white animate__animated animate__bounce">
        @csrf
        <h5 class="text-center text-dark">Demande de réinitialisation de compte</h5>
        <p class="">
            Entrer votre adresse mail pour faire une demande de réinitialiser votre mot de passe
        </p>
        <br>

        <div class="form-group">
            <input type="email" value="{{old('email')}}" name="email" class="form-control" placeholder="Votre adresse mail  ....">
            @error("email")
            <span class="text-danger"> {{$message}} </span>
            @enderror
        </div>

        <br>
        <button type="submit" class="btn bg-dark w-100">VALIDER</button>
        <div class="my-2 text-center">
            <a href="/" class="text-red" style="text-decoration: none;">
                <i class="bi bi-arrow-left-circle"></i> &nbsp;
                Retour
            </a>
        </div>
    </form>
    @else
    <form wire:submit="ReinitialisationComfirm" class="shadow-lg p-3 roundered bg-white animate__animated animate__bounce">
        @csrf
        <h5 class="text-center text-dark">Réinitialisation de compte</h5>
        <p class="">
            Entrer le <strong class="text-red">Code</strong> qui vous a été envoyé via votre adresse mail pour réinitialiser votre mot de passe
        </p>
        <br>
        <span class="text-red">{{$generalError}}</span>

        <div class="form-group">
            <div class="mb-3">
                <span class="text-red">{{$pass_code_error}}</span>
                <input type="text" wire:model="pass_code" name="pass_code" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Votre code  ....">
            </div>

            <div class="mb-3">
                <span class="text-red">{{$new_password_error}}</span>
                <input type="password" wire:model="new_password" name="new_password" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Votre nouveau mot de passe  ....">
            </div>
        </div>

        <br>
        <button type="submit" class="btn bg-dark w-100">CONFIRMER</button>
        <div class="my-2">
            <a href="/" class="text-red" style="text-decoration: none;">
                <i class="bi bi-arrow-left-circle"></i> &nbsp;
                Retour
            </a>
        </div>
    </form>
    @endif
</div>