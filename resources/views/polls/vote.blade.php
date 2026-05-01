<x-vue-app-layout>
    <x-slot:scripts>
        @vite(['resources/js/vote.js'])
    </x-slot>

    <x-slot:title>
        Sondage
    </x-slot>

    <div
        id="app"
        data-props='@json([
            "token" => $token,
            "loginUrl" => route("login"),
        ])'
    ></div>
</x-vue-app-layout>