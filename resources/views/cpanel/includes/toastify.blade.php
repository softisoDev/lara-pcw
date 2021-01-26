@if(session()->has('alert'))
    @php $alert = session()->get('alert'); @endphp
    <script>
        @if(isset($alert['position']))
            run_toastify("{{$alert['title']}}", "{{$alert['type']}}","{{$alert['position']}}");
        @else
            run_toastify("{{$alert['title']}}", "{{$alert['type']}}");
        @endif
    </script>
@endif
@php session()->remove('alert'); @endphp

