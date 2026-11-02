@extends('errors.layout')

@section('code', '422')
@section('title', 'Data Tidak Valid')
@section('message', $exception->getMessage() ?: 'Data yang dikirim tidak dapat diproses. Periksa kembali lalu coba lagi.')
