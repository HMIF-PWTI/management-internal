@extends('errors.layout')

@section('code', $exception->getStatusCode())
@section('title', 'Permintaan Tidak Dapat Diproses')
@section('message', 'Permintaan Anda tidak dapat diproses. Periksa kembali lalu coba lagi.')
