@extends('layouts.app')

@section('content')
<update-questions :questions="{{ $questions }}" :fail-score="{{ $fail_score }}">
    @csrf
</update-questions>
@endsection