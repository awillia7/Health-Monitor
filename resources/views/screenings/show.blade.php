@extends('layouts.app')

@section('content')
<screening :screening="{{ $screening }}" :override_role="{{ Auth::user()->hasRole('OVERRIDE') ? 1 : 0 }}" :delete_role="{{ Auth::user()->hasRole('DELETE') ? 1 : 0 }}" />
@endsection