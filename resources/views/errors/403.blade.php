@extends('errors.layout')

@section('code', '403')
@section('title', 'Akses Ditolak')
{{-- Pesan abort() kustom ditampilkan, pesan bawaan framework yang berbahasa Inggris tidak. --}}
@section('message', in_array($exception->getMessage(), ['', 'This action is unauthorized.', 'Invalid signature.'], true)
    ? 'Anda tidak memiliki izin untuk mengakses halaman ini. Hubungi administrator jika menurut Anda ini sebuah kesalahan.'
    : $exception->getMessage())
