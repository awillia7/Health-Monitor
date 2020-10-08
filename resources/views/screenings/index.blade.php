@extends('layouts.app')

@section('content')
<screenings :screenings="{{ $screenings }}" :override_role="{{ Auth::user()->hasRole('SCREENINGS_OVERRIDE') ? 1 : 0 }}" :delete_role="{{ Auth::user()->hasRole('SCREENINGS_DELETE') ? 1 : 0 }}" />
@endsection