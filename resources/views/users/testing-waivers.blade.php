@extends('layouts.app')

@section('content')
<testing-waivers :users="{{ $users }}" />
@endsection