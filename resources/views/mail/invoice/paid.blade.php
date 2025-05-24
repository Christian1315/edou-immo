<x-mail::message>
<h1 class="">{{$subject}}</h1> 
<br>
{{$message}}

<x-mail::button :url="''">
Visitez notre plateforme
</x-mail::button>

Merci <br>
Coordialement,<br>
{{ config('app.name') }}
</x-mail::message>
