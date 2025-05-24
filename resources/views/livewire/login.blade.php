<div>

    <form action="{{route('user.login')}}" method="POST" class="shadow-lg p-3 rounded bg-white animate__animated animate__bounce" >
        @csrf
        <h3 class="text-center text-dark">Connectez-vous ici! </h3>
        <div class="form-group">
            <input type="text" value="{{old('account')}}" autofocus name="account" class="form-control " placeholder="Votre identifiant ....">
            @error("account")
            <span class="text-danger"> {{$message}} </span>
            @enderror
        </div>
        <br>
        <div class="form-group">
            <input type="password" value="{{old('password')}}" name="password" class="form-control" placeholder="Password">
            @error("account")
            <span class="text-danger"> {{$message}} </span>
            @enderror
        </div>
        <br>
        <button type="submit" class="btn bg-dark w-100">SE CONNECTER</button>
        <div class="text-center">
            <br>
            <a href="/demande-reinitialisation" class="text-red" style="text-decoration: none;"> Réinitialisez votre compte <i class="bi bi-person-check"></i> !</a>
        </div>
    </form>
</div>