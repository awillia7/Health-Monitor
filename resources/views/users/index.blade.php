@extends('layouts.app')

@section('content')
<users :users="{{ $users }}" />
@endsection