@component('mail::button', ['url' => $url])
View Screening
@endcomponent

<br />

@component('mail::table')
| {{ $user->name }} ({{ $user->erp_id }}) |  |
| --- | :---: |
@foreach ($answers as $answer)
| {{ $answer->question->text }} | @if($answer->value > 0) <span style="color:#5cb85c;">Yes</span> @else <span style="color:#d9534f;">No</span> @endif |
@endforeach
@endcomponent