<strong>Take care about:</strong><em>{{ get_class($e) }} [{{ $e->getCode() }}]</em>
@if($m = $e->getMessage())
  <br>
  <strong>It say: </strong><i>{{ $m }}</i>
@endif
<br>
<strong>At: </strong><i>{{ $file }}: {{ $line }}</i>

<br>
@if($getRequest)
  <strong>GET</strong> <a href="{{ $location }}" target="_blank">{{ $location }}</a> <br>
  <strong>After:</strong> <a href="{{ $prev }}" target="_blank">{{ $prev }}</a>
@else
  <strong>{{ $method }}</strong> to: <code>{{ $location }}</code><br>
  @if($payload)
    <pre>{!! print_r($payload, true) !!}</pre>
  @endif
  <br>
  <strong>From:</strong> <a href="{{ $prev }}" target="_blank">{{ $prev }}</a>
@endif
