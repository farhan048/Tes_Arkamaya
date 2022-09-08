@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('main-content')
<x-page-layout>
    @slot('pageTitle')Dashboard @endslot
    @slot('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
    @endslot

    @slot('title')  You're logged in! @endslot
    @slot('button')           
    @endslot
    @slot('table')
    
    @endslot
    @slot('modal')
        
    @endslot
</x-page-layout>

@endsection