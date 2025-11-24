@extends('layouts.loginAndRegister')

@section('title', 'Iniciar sesión')

{{-- Ocultar menú / header / footer en login --}}
@section('hide_menu', true)
@section('hide_header', true)
@section('hide_footer', true)

@section('content')
<div  class="min-h-screen flex items-center justify-center px-4 relative" id="loginForm">

    {{-- LISTA DE SALAS --}}
    @if(!$showQR)
        <div class="w-full max-w-5xl grid gap-6">

            <h1 class="text-3xl text-center mb-8 text-white font-bold">
                Selecciona la sala
            </h1>

            @foreach($rooms as $room)
                <button wire:click="selectRoom({{ $room->id }})"
                    class="block bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition-all group">

                    <div class="flex flex-col sm:flex-row">

                        {{-- ÍCONO QR --}}
                        <div class="sm:w-1/4 flex items-center justify-center text-white h-48 sm:h-auto bg-gradient-to-br from-cyan-400 to-cyan-600">
                            <i class="fa-solid fa-qrcode text-4xl"></i>
                        </div>

                        <div class="sm:w-3/4 p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-cyan-600 transition-colors">
                                Salón # {{ $room->id }}
                            </h3>
                            <p class="text-gray-600 text-sm">
                                {{ $room->description }}
                            </p>
                        </div>

                    </div>

                </button>
            @endforeach

        </div>
    @endif


    {{-- QR MOSTRADO --}}
    @if($showQR)
        <div class="flex flex-col items-center gap-6">

            <h2 class="text-white text-2xl font-bold">Escanea este código</h2>

            {{-- Imagen del QR --}}
            @if($qrImage)
                <img src="{{ $qrImage }}" class="w-72 h-72 bg-white p-2 rounded-xl shadow-lg">
            @endif

            <button wire:click="$set('showQR', false)"
                class="bg-white px-6 py-3 rounded-xl shadow-md hover:shadow-lg">
                Volver
            </button>

        </div>
    @endif
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
        document.addEventListener('livewire:initialized', function () {

            Livewire.on('generate-qr', (data) => {

                const canvas = document.getElementById('qrCanvas');

                // Limpia antes de generar
                const ctx = canvas.getContext('2d');
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                new QRious({
                    element: canvas,
                    value: data,
                    size: 500
                });
            });

            Livewire.on('swal:notify', e => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: e[0].message,
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                });
            });

        });
        </script>

