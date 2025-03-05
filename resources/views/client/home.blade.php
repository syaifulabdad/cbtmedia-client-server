@extends('client.layouts.master')
@section('title')
    {{ $title }}
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Home
        @endslot
        @slot('title')
            {{ $title }}
        @endslot
    @endcomponent
@endsection
