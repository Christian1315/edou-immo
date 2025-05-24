<x-home>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <form action="{{route('demandeReinitialisation')}}" method="POST" class="shadow-lg p-3 rounded bg-white animate__animated animate__bounce">
                @csrf
                <h5 class="text-center text-dark">Demande de réinitialisation de compte</h5>
                <p class="">
                    Entrer votre adresse mail pour faire une demande de réinitialiser votre mot de passe
                </p>
                <br>

                <div class="form-group">
                    <input type="text" value="{{old('email')}}" name="email" class="form-control" placeholder="Votre adresse mail  ....">
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
        </div>
        <div class="col-md-4"></div>
    </div>
</x-home>