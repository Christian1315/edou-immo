<x-home>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <form action="{{route('Reinitialisation')}}" method="POST" class="shadow-lg p-3 roundered bg-white animate__animated animate__bounce">
                @csrf
                <h5 class="text-center text-dark">Réinitialisation de compte</h5>
                <p class="">
                    Entrer le <strong class="text-red">Code</strong> qui vous a été envoyé via votre adresse mail pour réinitialiser votre mot de passe
                </p>

                <div class="form-group">
                    <div class="mb-3">
                        <input type="text" value="{{old('pass_code')}}" name="pass_code" class="form-control" placeholder="Votre code  ....">
                        @error("pass_code")
                        <span class="text-danger"> {{$message }} </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <input type="password" value="{{old('password')}}" name="password" class="form-control" placeholder="Votre nouveau mot de passe  ....">
                        @error("password")
                        <span class="text-danger"> {{$message }} </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <input type="password" value="{{old('password_confirmation')}}" name="password_confirmation" class="form-control" placeholder="Confirmez votre mot de passe  ....">
                    </div>
                </div>
                <br>
                <button type="submit" class="btn bg-dark w-100">CONFIRMER</button>
                <div class="my-2 text-center">
                    <a href="/" class="text-red" style="text-decoration: none;">
                        <i class="bi bi-arrow-left-circle"></i> &nbsp;
                        Retour
                    </a>
                </div>
            </form>
        </div>
        <div class="col-md-4"></div>
    </div>
</x-home>