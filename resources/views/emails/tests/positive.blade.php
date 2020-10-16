@component('mail::table')
| Name | Test Date |
| :---: | :---: |
@foreach ($positive_tests as $test)
| {{ $test->user->name }} | {{ $test->formatted_test_date }} |
@endforeach
@endcomponent