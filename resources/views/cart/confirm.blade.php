@extends('layouts.app')
@section('content')
<form action="{{ route('cart.order') }}" method="post">
@csrf
@include ('cart.components.confirm_item_list')
</form>
@include ('cart.components.confirm_control')
@endsection