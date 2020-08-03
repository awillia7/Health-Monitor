@extends('layouts.app')

@section('content')
<screenings :screenings="{{ $screenings }}" />
@endsection