<x-vue-app-layout>
    <x-slot:scripts>
        @vite(['resources/js/poll-dashboard.js'])
    </x-slot>

    <x-slot:title>
        Sondages
    </x-slot>

    <div
        id="app"
        data-props='@json([
            "polls" => $polls,
            "loginUrl" => route("login")],
            JSON_HEX_APOS
        )'
        {{-- ici le JSON_HEX_APOS m'aide à éviter les bugs sur les '' dans la data que je passe. --}}
    ></div>
</x-vue-app-layout>
