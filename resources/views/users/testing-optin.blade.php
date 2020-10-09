@extends('layouts.app')

@section('content')
<testing-optin :user="{{ $user }}" :test="{{ $test }}" />
@endsection