@extends('layouts.app')

@section('content')
<update-questions :questions="{{ $questions }}" />
@endsection