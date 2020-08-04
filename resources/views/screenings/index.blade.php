@extends('layouts.app')

@section('content')
<screenings :screenings="{{ $screenings }}" :override="{{ Auth::user()->hasRole('OVERRIDE') ? 1 : 0 }}" />
@endsection