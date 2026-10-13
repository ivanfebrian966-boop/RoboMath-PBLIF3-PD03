@extends('layouts.guest')

@section('title', 'Masuk - RoboMath')

@section('content')
<script>window.location.href = "{{ route('login') }}";</script>
<meta http-equiv="refresh" content="0;url={{ route('login') }}">
@endsection
