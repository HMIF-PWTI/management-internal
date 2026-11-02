@extends('errors.layout')

@section('code', $exception->getStatusCode())
@section('title', 'Layanan Sedang Bermasalah')
@section('message', 'Layanan sedang tidak dapat diakses. Coba lagi beberapa saat lagi.')
