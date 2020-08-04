@extends('layouts.app')

@section('content')
<screening :screening="{{ $screening }}" :override="{{ Auth::user()->hasRole('OVERRIDE') ? 1 : 0 }}" />
@endsection