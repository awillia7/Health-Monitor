@component('mail::button', ['url' => $url])
View Screening
@endcomponent

<br />

@component('mail::table')
| {{ $user->name }} ({{ $user->erp_id }}) - <a href="mailto:{{ $user->email }}">{{ $user->email }}</a> |  |
| --- | :---: |
@foreach ($answers as $answer)
| {{ $answer->question->text }} | @if($answer->value > 0) <span style="color:#d9534f;">Yes</span> @else <span style="color:#5cb85c;">No</span> @endif |
@endforeach
@endcomponent