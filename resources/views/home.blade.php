@extends('layouts.app')

@section('content')

@if ($screening)
    <screening-code :screening="{{ $screening }}" />
@else
    <screening-form :questions="{{ $questions }}">
        @csrf
    </screening-form>
@endif

@endsection
